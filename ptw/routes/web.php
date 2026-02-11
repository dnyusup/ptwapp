<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermitToWorkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MethodStatementController;
use App\Http\Controllers\EmergencyPlanController;
use App\Http\Controllers\KontraktorListController;
use App\Http\Controllers\HraWorkAtHeightController;
use App\Http\Controllers\HraHotWorkController;
use App\Http\Controllers\HraLotoIsolationController;
use App\Http\Controllers\HraLineBreakingController;
use App\Http\Controllers\HraExcavationController;
use App\Http\Controllers\HraConfinedSpaceController;
use App\Http\Controllers\HraExplosiveAtmosphereController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\ContractorUserController;

// Route untuk serve file storage (bypass symlink issue di shared hosting)
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($fullPath);
    return Response::file($fullPath, ['Content-Type' => $mimeType]);
})->where('path', '.*')->name('storage.serve');

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('permits', PermitToWorkController::class);
    
    // Test route for expired permits functionality
    Route::get('/test/expired-permits', function() {
        $now = \Carbon\Carbon::now();
        
        // Get counts before update
        $activeCount = \App\Models\PermitToWork::where('status', 'active')->count();
        $expiredCount = \App\Models\PermitToWork::where('status', 'expired')->count();
        
        // Run the update
        $updatedCount = \App\Models\PermitToWork::updateExpiredPermits();
        
        // Get counts after update
        $activeCountAfter = \App\Models\PermitToWork::where('status', 'active')->count();
        $expiredCountAfter = \App\Models\PermitToWork::where('status', 'expired')->count();
        
        return response()->json([
            'current_date' => $now->format('Y-m-d H:i:s'),
            'cutoff_date' => $now->startOfDay()->format('Y-m-d H:i:s'),
            'before_update' => [
                'active_permits' => $activeCount,
                'expired_permits' => $expiredCount,
            ],
            'after_update' => [
                'active_permits' => $activeCountAfter,
                'expired_permits' => $expiredCountAfter,
            ],
            'permits_updated' => $updatedCount,
            'message' => $updatedCount > 0 ? 
                "{$updatedCount} permit(s) updated from active to expired" : 
                "No permits needed to be updated"
        ]);
    })->name('test.expired');
    
    // Test route for extension debugging
    Route::get('/test/extension-debug/{permit}', function(\App\Models\PermitToWork $permit) {
        $user = auth()->user();
        return response()->json([
            'permit_info' => [
                'id' => $permit->id,
                'permit_number' => $permit->permit_number,
                'status' => $permit->status,
                'end_date' => $permit->end_date->format('Y-m-d'),
                'permit_issuer_id' => $permit->permit_issuer_id,
            ],
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
            'permissions' => [
                'is_permit_creator' => ($user->id == $permit->permit_issuer_id),
                'is_admin' => ($user->role === 'administrator'),
                'can_extend' => (($user->id == $permit->permit_issuer_id) || ($user->role === 'administrator')) && ($permit->status === 'expired'),
            ],
            'extend_route' => route('permits.extend', $permit),
        ]);
    })->name('test.extension.debug');
    
    // Test route for complete debugging
    Route::get('/test/complete-debug/{permit}', function(\App\Models\PermitToWork $permit) {
        $user = auth()->user();
        return response()->json([
            'permit_info' => [
                'id' => $permit->id,
                'permit_number' => $permit->permit_number,
                'status' => $permit->status,
                'permit_issuer_id' => $permit->permit_issuer_id,
            ],
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
            'permissions' => [
                'is_permit_creator' => ($user->id == $permit->permit_issuer_id),
                'is_admin' => ($user->role === 'administrator'),
                'can_complete' => (($user->id == $permit->permit_issuer_id) || ($user->role === 'administrator')) && in_array($permit->status, ['active', 'expired']),
            ],
            'complete_route' => route('permits.complete', $permit),
        ]);
    })->name('test.complete.debug');
    
    // Test route for extension approval email
    Route::get('/test/extension-approval-email/{permit}', function(\App\Models\PermitToWork $permit) {
        $user = auth()->user();
        $creatorEmail = $permit->permitIssuer->email ?? null;
        
        if (!$creatorEmail) {
            return response()->json([
                'error' => 'No creator email found',
                'permit_id' => $permit->id,
                'permit_issuer' => $permit->permitIssuer ? $permit->permitIssuer->name : 'null'
            ]);
        }
        
        try {
            // Test sending approval email
            \Mail::to($creatorEmail)->send(new \App\Mail\PermitApprovalResult($permit, true, 'extension'));
            
            return response()->json([
                'success' => true,
                'message' => 'Extension approval email sent successfully',
                'permit_id' => $permit->id,
                'to_email' => $creatorEmail,
                'permit_status' => $permit->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send email',
                'message' => $e->getMessage(),
                'permit_id' => $permit->id,
                'to_email' => $creatorEmail
            ]);
        }
    })->name('test.extension.approval.email');
    
    // Test route for email preview
    Route::get('/test/email-preview/{permit}/{type}/{result}', function(\App\Models\PermitToWork $permit, $type, $result) {
        $resultBool = ($result === 'approved') ? true : false;
        
        $mailable = new \App\Mail\PermitApprovalResult($permit, $resultBool, $type);
        return $mailable->render();
    })->name('test.email.preview');
    
        // Additional permit actions
    Route::post('/permits/{permit}/submit', [PermitToWorkController::class, 'submit'])->name('permits.submit');
    Route::post('/permits/{permit}/approve', [PermitToWorkController::class, 'approve'])->name('permits.approve');
    Route::post('/permits/{permit}/reject', [PermitToWorkController::class, 'reject'])->name('permits.reject');
    Route::post('/permits/{permit}/resubmit', [PermitToWorkController::class, 'resubmit'])->name('permits.resubmit');
    Route::post('/permits/{permit}/request-approval', [PermitToWorkController::class, 'requestApproval'])->name('permits.request-approval');
    Route::patch('/permits/{permit}/complete', [PermitToWorkController::class, 'complete'])->name('permits.complete');
    Route::patch('/permits/{permit}/extend', [PermitToWorkController::class, 'extend'])->name('permits.extend');
    Route::post('/permits/{permit}/approve-extension', [PermitToWorkController::class, 'approveExtension'])->name('permits.approve-extension');
    Route::post('/permits/{permit}/reject-extension', [PermitToWorkController::class, 'rejectExtension'])->name('permits.reject-extension');
    Route::get('/permits/{permit}/download-pdf', [PermitToWorkController::class, 'downloadPdf'])->name('permits.download-pdf');
    
    // User management routes (Administrator only)
    Route::resource('users', UserController::class);
    // Activation route for pending users (EHS only)
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    
    // Contractor User management routes (Contractor role only - manage their team)
    Route::resource('contractor-users', ContractorUserController::class);
    
    // Kontraktor List management routes (Administrator and Bekaert EHS only)
    Route::resource('kontraktor-lists', KontraktorListController::class);
    
    // Method Statement routes
    Route::get('/permits/{permitNumber}/method-statement/create', [MethodStatementController::class, 'create'])->name('method-statements.create');
    Route::post('/permits/{permitNumber}/method-statement', [MethodStatementController::class, 'store'])->name('method-statements.store');
    Route::get('/permits/{permitNumber}/method-statement', [MethodStatementController::class, 'show'])->name('method-statements.show');
    Route::get('/permits/{permitNumber}/method-statement/edit', [MethodStatementController::class, 'edit'])->name('method-statements.edit');
    Route::put('/permits/{permitNumber}/method-statement', [MethodStatementController::class, 'update'])->name('method-statements.update');
    Route::post('/permits/{permitNumber}/method-statement/request-approval', [MethodStatementController::class, 'requestApproval'])->name('method-statements.request-approval');
    Route::post('/permits/{permitNumber}/method-statement/approve', [MethodStatementController::class, 'approve'])->name('method-statements.approve');
    Route::post('/permits/{permitNumber}/method-statement/reject', [MethodStatementController::class, 'reject'])->name('method-statements.reject');
    
    // Emergency Plan routes
    Route::get('/permits/{permitNumber}/emergency-plan/create', [EmergencyPlanController::class, 'create'])->name('emergency-plans.create');
    Route::post('/permits/{permitNumber}/emergency-plan', [EmergencyPlanController::class, 'store'])->name('emergency-plans.store');
    Route::get('/permits/{permitNumber}/emergency-plan', [EmergencyPlanController::class, 'show'])->name('emergency-plans.show');
    Route::get('/permits/{permitNumber}/emergency-plan/edit', [EmergencyPlanController::class, 'edit'])->name('emergency-plans.edit');
    Route::put('/permits/{permitNumber}/emergency-plan', [EmergencyPlanController::class, 'update'])->name('emergency-plans.update');
    Route::post('/permits/{permitNumber}/emergency-plan/request-approval', [EmergencyPlanController::class, 'requestApproval'])->name('emergency-plans.request-approval');
    Route::post('/permits/{permitNumber}/emergency-plan/approve', [EmergencyPlanController::class, 'approve'])->name('emergency-plans.approve');
    Route::post('/permits/{permitNumber}/emergency-plan/reject', [EmergencyPlanController::class, 'reject'])->name('emergency-plans.reject');
    
    // HRA Work at Heights routes
    Route::get('/permits/{permit}/hra/work-at-heights', [HraWorkAtHeightController::class, 'index'])->name('hra.work-at-heights.index');
    Route::get('/permits/{permit}/hra/work-at-heights/create', [HraWorkAtHeightController::class, 'create'])->name('hra.work-at-heights.create');
    Route::post('/permits/{permit}/hra/work-at-heights', [HraWorkAtHeightController::class, 'store'])->name('hra.work-at-heights.store');
    Route::get('/permits/{permit}/hra/work-at-heights/{hraWorkAtHeight}', [HraWorkAtHeightController::class, 'show'])->name('hra.work-at-heights.show');
    Route::get('/permits/{permit}/hra/work-at-heights/{hraWorkAtHeight}/edit', [HraWorkAtHeightController::class, 'edit'])->name('hra.work-at-heights.edit');
    Route::put('/permits/{permit}/hra/work-at-heights/{hraWorkAtHeight}', [HraWorkAtHeightController::class, 'update'])->name('hra.work-at-heights.update');
    Route::delete('/permits/{permit}/hra/work-at-heights/{hraWorkAtHeight}', [HraWorkAtHeightController::class, 'destroy'])->name('hra.work-at-heights.destroy');
    Route::get('/permits/{permit}/hra/work-at-heights/{hraWorkAtHeight}/download-pdf', [HraWorkAtHeightController::class, 'downloadPdf'])->name('hra.work-at-heights.download-pdf');
    // HRA Work at Heights Approval routes
    Route::post('/permits/{permit}/hra/work-at-heights/{hraWorkAtHeight}/request-approval', [HraWorkAtHeightController::class, 'requestApproval'])->name('hra.work-at-heights.request-approval');
    Route::post('/permits/{permit}/hra/work-at-heights/{hraWorkAtHeight}/process', [HraWorkAtHeightController::class, 'processApproval'])->name('hra.work-at-heights.process');
    
    // HRA Hot Work routes
    Route::get('/permits/{permit}/hra/hot-works', [HraHotWorkController::class, 'index'])->name('hra.hot-works.index');
    Route::get('/permits/{permit}/hra/hot-works/create', [HraHotWorkController::class, 'create'])->name('hra.hot-works.create');
    Route::post('/permits/{permit}/hra/hot-works', [HraHotWorkController::class, 'store'])->name('hra.hot-works.store');
    Route::get('/permits/{permit}/hra/hot-works/{hraHotWork}', [HraHotWorkController::class, 'show'])->name('hra.hot-works.show');
    Route::get('/permits/{permit}/hra/hot-works/{hraHotWork}/edit', [HraHotWorkController::class, 'edit'])->name('hra.hot-works.edit');
    Route::put('/permits/{permit}/hra/hot-works/{hraHotWork}', [HraHotWorkController::class, 'update'])->name('hra.hot-works.update');
    Route::delete('/permits/{permit}/hra/hot-works/{hraHotWork}', [HraHotWorkController::class, 'destroy'])->name('hra.hot-works.destroy');
    
    // HRA Hot Work approval routes
    Route::post('/permits/{permit}/hra/hot-works/{hraHotWork}/request-approval', [HraHotWorkController::class, 'requestApproval'])->name('hra.hot-works.request-approval');
    Route::get('/permits/{permit}/hra/hot-works/{hraHotWork}/approve', [HraHotWorkController::class, 'approve'])->name('hra.hot-works.approve');
    Route::get('/permits/{permit}/hra/hot-works/{hraHotWork}/reject', [HraHotWorkController::class, 'reject'])->name('hra.hot-works.reject');
    Route::post('/permits/{permit}/hra/hot-works/{hraHotWork}/process', [HraHotWorkController::class, 'processApproval'])->name('hra.hot-works.process-approval');
    Route::get('/permits/{permit}/hra/hot-works/{hraHotWork}/download-pdf', [HraHotWorkController::class, 'downloadPdf'])->name('hra.hot-works.download-pdf');
    
    // HRA LOTO/Isolation routes
    Route::get('/permits/{permit}/hra/loto-isolations', [HraLotoIsolationController::class, 'index'])->name('hra.loto-isolations.index');
    Route::get('/permits/{permit}/hra/loto-isolations/create', [HraLotoIsolationController::class, 'create'])->name('hra.loto-isolations.create');
    Route::post('/permits/{permit}/hra/loto-isolations', [HraLotoIsolationController::class, 'store'])->name('hra.loto-isolations.store');
    Route::get('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}', [HraLotoIsolationController::class, 'show'])->name('hra.loto-isolations.show');
    Route::get('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}/edit', [HraLotoIsolationController::class, 'edit'])->name('hra.loto-isolations.edit');
    Route::put('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}', [HraLotoIsolationController::class, 'update'])->name('hra.loto-isolations.update');
    Route::delete('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}', [HraLotoIsolationController::class, 'destroy'])->name('hra.loto-isolations.destroy');
    Route::get('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}/download-pdf', [HraLotoIsolationController::class, 'downloadPdf'])->name('hra.loto-isolations.download-pdf');
    
    // HRA LOTO/Isolation approval routes
    Route::post('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}/request-approval', [HraLotoIsolationController::class, 'requestApproval'])->name('hra.loto-isolations.request-approval');
    Route::get('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}/approve', [HraLotoIsolationController::class, 'approve'])->name('hra.loto-isolations.approve');
    Route::get('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}/reject', [HraLotoIsolationController::class, 'reject'])->name('hra.loto-isolations.reject');
    Route::post('/permits/{permit}/hra/loto-isolations/{hraLotoIsolation}/process', [HraLotoIsolationController::class, 'processApproval'])->name('hra.loto-isolations.process-approval');
    
    // HRA Line Breaking routes
    Route::get('/permits/{permit}/hra/line-breakings', [HraLineBreakingController::class, 'index'])->name('hra.line-breakings.index');
    Route::get('/permits/{permit}/hra/line-breakings/create', [HraLineBreakingController::class, 'create'])->name('hra.line-breakings.create');
    Route::post('/permits/{permit}/hra/line-breakings', [HraLineBreakingController::class, 'store'])->name('hra.line-breakings.store');
    Route::get('/permits/{permit}/hra/line-breakings/{hraLineBreaking}', [HraLineBreakingController::class, 'show'])->name('hra.line-breakings.show');
    Route::get('/permits/{permit}/hra/line-breakings/{hraLineBreaking}/edit', [HraLineBreakingController::class, 'edit'])->name('hra.line-breakings.edit');
    Route::put('/permits/{permit}/hra/line-breakings/{hraLineBreaking}', [HraLineBreakingController::class, 'update'])->name('hra.line-breakings.update');
    Route::delete('/permits/{permit}/hra/line-breakings/{hraLineBreaking}', [HraLineBreakingController::class, 'destroy'])->name('hra.line-breakings.destroy');
    
    // HRA Excavation routes
    Route::get('/permits/{permit}/hra/excavations', [HraExcavationController::class, 'index'])->name('hra.excavations.index');
    Route::get('/permits/{permit}/hra/excavations/create', [HraExcavationController::class, 'create'])->name('hra.excavations.create');
    Route::post('/permits/{permit}/hra/excavations', [HraExcavationController::class, 'store'])->name('hra.excavations.store');
    Route::get('/permits/{permit}/hra/excavations/{hraExcavation}', [HraExcavationController::class, 'show'])->name('hra.excavations.show');
    Route::get('/permits/{permit}/hra/excavations/{hraExcavation}/edit', [HraExcavationController::class, 'edit'])->name('hra.excavations.edit');
    Route::put('/permits/{permit}/hra/excavations/{hraExcavation}', [HraExcavationController::class, 'update'])->name('hra.excavations.update');
    Route::delete('/permits/{permit}/hra/excavations/{hraExcavation}', [HraExcavationController::class, 'destroy'])->name('hra.excavations.destroy');
    
    // HRA Confined Space routes
    Route::get('/permits/{permit}/hra/confined-spaces', [HraConfinedSpaceController::class, 'index'])->name('hra.confined-spaces.index');
    Route::get('/permits/{permit}/hra/confined-spaces/create', [HraConfinedSpaceController::class, 'create'])->name('hra.confined-spaces.create');
    Route::post('/permits/{permit}/hra/confined-spaces', [HraConfinedSpaceController::class, 'store'])->name('hra.confined-spaces.store');
    Route::get('/permits/{permit}/hra/confined-spaces/{hraConfinedSpace}', [HraConfinedSpaceController::class, 'show'])->name('hra.confined-spaces.show');
    Route::get('/permits/{permit}/hra/confined-spaces/{hraConfinedSpace}/edit', [HraConfinedSpaceController::class, 'edit'])->name('hra.confined-spaces.edit');
    Route::put('/permits/{permit}/hra/confined-spaces/{hraConfinedSpace}', [HraConfinedSpaceController::class, 'update'])->name('hra.confined-spaces.update');
    Route::delete('/permits/{permit}/hra/confined-spaces/{hraConfinedSpace}', [HraConfinedSpaceController::class, 'destroy'])->name('hra.confined-spaces.destroy');
    
    // HRA Explosive Atmosphere routes
    Route::get('/permits/{permit}/hra/explosive-atmospheres', [HraExplosiveAtmosphereController::class, 'index'])->name('hra.explosive-atmospheres.index');
    Route::get('/permits/{permit}/hra/explosive-atmospheres/create', [HraExplosiveAtmosphereController::class, 'create'])->name('hra.explosive-atmospheres.create');
    Route::post('/permits/{permit}/hra/explosive-atmospheres', [HraExplosiveAtmosphereController::class, 'store'])->name('hra.explosive-atmospheres.store');
    Route::get('/permits/{permit}/hra/explosive-atmospheres/{hraExplosiveAtmosphere}', [HraExplosiveAtmosphereController::class, 'show'])->name('hra.explosive-atmospheres.show');
    Route::get('/permits/{permit}/hra/explosive-atmospheres/{hraExplosiveAtmosphere}/edit', [HraExplosiveAtmosphereController::class, 'edit'])->name('hra.explosive-atmospheres.edit');
    Route::put('/permits/{permit}/hra/explosive-atmospheres/{hraExplosiveAtmosphere}', [HraExplosiveAtmosphereController::class, 'update'])->name('hra.explosive-atmospheres.update');
    Route::delete('/permits/{permit}/hra/explosive-atmospheres/{hraExplosiveAtmosphere}', [HraExplosiveAtmosphereController::class, 'destroy'])->name('hra.explosive-atmospheres.destroy');
    
    // Inspection routes
    Route::get('/permits/{permitNumber}/inspections', [InspectionController::class, 'index'])->name('inspections.index');
    Route::post('/permits/{permitNumber}/inspections', [InspectionController::class, 'store'])->name('inspections.store');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.update-profile');
});
