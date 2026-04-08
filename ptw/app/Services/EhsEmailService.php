<?php

namespace App\Services;

use App\Models\User;
use App\Models\Area;

class EhsEmailService
{
    /**
     * Get EHS email recipients based on permit's area
     * 
     * If permit has an area_id and the area has responsible users, return those users' emails.
     * Otherwise, fallback to all EHS department users.
     * 
     * @param int|null $areaId The area_id from the permit
     * @return array Array of email addresses
     */
    public static function getEhsEmails(?int $areaId): array
    {
        $ehsUsers = collect();

        // Check if area_id is set and area has responsibles
        if ($areaId) {
            $area = Area::with('responsibles')->find($areaId);
            
            if ($area && $area->responsibles->count() > 0) {
                $ehsUsers = $area->responsibles;
                \Log::info('Using area responsibles for EHS emails', [
                    'area_id' => $areaId,
                    'area_name' => $area->name,
                    'responsible_count' => $ehsUsers->count()
                ]);
            }
        }

        // Fallback to all EHS users if no area or no area responsibles
        if ($ehsUsers->isEmpty()) {
            $ehsUsers = User::where('role', 'bekaert')
                           ->where('department', 'EHS')
                           ->get();
            \Log::info('Using all EHS users for emails', [
                'area_id' => $areaId,
                'ehs_count' => $ehsUsers->count()
            ]);
        }

        return $ehsUsers->pluck('email')->filter()->unique()->toArray();
    }

    /**
     * Get EHS users (model instances) based on permit's area
     * 
     * @param int|null $areaId The area_id from the permit
     * @return \Illuminate\Support\Collection Collection of User models
     */
    public static function getEhsUsers(?int $areaId): \Illuminate\Support\Collection
    {
        // Check if area_id is set and area has responsibles
        if ($areaId) {
            $area = Area::with('responsibles')->find($areaId);
            
            if ($area && $area->responsibles->count() > 0) {
                return $area->responsibles;
            }
        }

        // Fallback to all EHS users
        return User::where('role', 'bekaert')
                   ->where('department', 'EHS')
                   ->get();
    }
}
