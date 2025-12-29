<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HRA Hot Work - {{ $hraHotWork->hra_permit_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 15px; 
            color: black;
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px;
            border-bottom: 2px solid black;
            padding-bottom: 8px;
        }
        .title { 
            font-size: 18px; 
            font-weight: bold; 
        }
        .section { 
            margin-bottom: 10px; 
            border: 1px solid black;
            padding: 8px;
        }
        .section-title { 
            font-weight: bold; 
            font-size: 14px;
            margin-bottom: 8px;
            background-color: #f5f5f5;
            padding: 4px;
            margin: -8px -8px 8px -8px;
        }
        .row { 
            margin-bottom: 5px; 
            line-height: 1.3;
        }
        .label { 
            font-weight: bold; 
            display: inline-block;
            width: 170px;
        }
        .long-text-row {
            margin-bottom: 6px;
        }
        .long-text-label {
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }
        .long-text-value {
            border: 1px solid #ccc;
            padding: 4px;
            background-color: #f9f9f9;
            min-height: 20px;
        }
        .checklist {
            margin-bottom: 15px;
        }
        .checklist-header {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            background-color: #007bff;
            color: white;
            padding: 6px;
            text-align: center;
        }
        .checklist-item {
            display: table;
            width: 100%;
            margin-bottom: 6px;
            border-bottom: 1px solid #eee;
            padding: 3px 0;
        }
        .checklist-question {
            display: table-cell;
            width: 80%;
            font-size: 11px;
            padding-right: 10px;
            vertical-align: middle;
        }
        .checklist-answer {
            display: table-cell;
            width: 20%;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }
        .yes { color: green; }
        .no { color: red; }
        .na { color: #666; }
        .two-column {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .fire-safety-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .fire-safety-row {
            display: table-row;
        }
        .fire-safety-cell {
            display: table-cell;
            border: 1px solid #ccc;
            padding: 4px;
            text-align: center;
            width: 20%;
            font-size: 10px;
        }
        .fire-safety-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .sub-question {
            font-style: italic;
            color: #666;
            margin-left: 15px;
            font-size: 10px;
        }
        .approval-section {
            margin-top: 15px;
            border: 2px solid #28a745;
            background-color: #f8f9fa;
        }
        .approval-title {
            background-color: #28a745;
            color: white;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: table; width: 100%; margin-bottom: 5px;">
            <div style="display: table-cell; width: 100px; vertical-align: top;">
                @if(isset($qrCode))
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 80px; height: 80px;">
                @else
                    <div style="width: 80px; height: 80px; border: 2px solid black; text-align: center; font-size: 8px; padding: 5px; background: white;">
                        <div style="font-weight: bold; margin-bottom: 5px;">QR CODE</div>
                        <div style="font-size: 6px; line-height: 1.1;">Scan to view HRA details</div>
                        <div style="font-size: 5px; margin-top: 5px; word-wrap: break-word;">{{ $hraHotWork->hra_permit_number }}</div>
                    </div>
                @endif
            </div>
            <div style="display: table-cell; text-align: center; vertical-align: middle; padding-left: 20px;">
                <div class="title">HRA HOT WORK ASSESSMENT</div>
                <div style="font-size: 14px; margin-top: 5px;">{{ $hraHotWork->hra_permit_number }}</div>
                <div style="font-size: 10px; margin-top: 3px;">Main Permit: {{ $permit->permit_number }}</div>
            </div>
            <div style="display: table-cell; width: 100px; text-align: right; vertical-align: top; font-size: 10px;">
                <div style="font-weight: bold;">Generated:</div>
                <div>{{ date('d/m/Y H:i') }}</div>
                <div style="margin-top: 5px; font-weight: bold;">Status:</div>
                <div style="color: green;">{{ strtoupper($hraHotWork->approval_status) }}</div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="section">
        <div class="section-title">BASIC INFORMATION</div>
        <div class="two-column">
            <div class="column">
                <div class="row">
                    <span class="label">Worker Name:</span>
                    {{ $hraHotWork->worker_name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Phone Number:</span>
                    {{ $hraHotWork->worker_phone ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Supervisor Name:</span>
                    {{ $hraHotWork->supervisor_name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Work Location:</span>
                    {{ $hraHotWork->work_location ?? 'N/A' }}
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="label">Start Date & Time:</span>
                    {{ $hraHotWork->start_datetime ? $hraHotWork->start_datetime->format('d/m/Y H:i') : 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">End Date & Time:</span>
                    {{ $hraHotWork->end_datetime ? $hraHotWork->end_datetime->format('d/m/Y H:i') : 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Created By:</span>
                    {{ $hraHotWork->user->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Main Permit:</span>
                    {{ $permit->permit_number }}
                </div>
            </div>
        </div>
        <div class="long-text-row">
            <div class="long-text-label">Work Description:</div>
            <div class="long-text-value">{{ $hraHotWork->work_description ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Hot Work Safety Checklist -->
    <div class="section">
        <div class="section-title">HRA HOT WORK ASSESSMENT</div>
        
        <!-- Section 1: Requirements within 11m/35ft from hot work -->
        <div style="font-weight: bold; font-size: 12px; margin: 10px 0; background-color: #e8f4f8; padding: 5px; border-left: 4px solid #007bff;">
            Bagian 1: Persyaratan dalam jarak 11m/35ft dari pekerjaan panas (termasuk di atas dan di bawah area kerja)
        </div>

        <div class="checklist-item">
            <div class="checklist-question">1. Semua bahan yang mudah terbakar disingkirkan atau dilindungi dengan penutup tahan api</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_materials_removed ? 'yes' : 'no' }}">
                {{ $hraHotWork->flammable_materials_removed ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">2. Cairan mudah terbakar, debu, serat, dan endapan minyak dihilangkan (debu di "dalam" dinding/atap/rongga)</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_liquids_removed ? 'yes' : 'no' }}">
                {{ $hraHotWork->flammable_liquids_removed ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">3. Lantai yang mudah terbakar dibasahi, ditutup dengan pasir basah atau penutup tahan api yang tumpang tindih</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_floors_wetted ? 'yes' : 'no' }}">
                {{ $hraHotWork->flammable_floors_wetted ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">4. Dinding/langit-langit/atap yang mudah terbakar dilindungi dengan penutup tahan api</div>
            <div class="checklist-answer {{ $hraHotWork->walls_ceiling_protected ? 'yes' : 'no' }}">
                {{ $hraHotWork->walls_ceiling_protected ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">5. Lantai disapu bersih dari bahan yang mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->floors_swept_clean ? 'yes' : 'no' }}">
                {{ $hraHotWork->floors_swept_clean ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">6. Material mudah terbakar di sisi lain dinding, langit-langit atau atap disingkirkan (perhatikan insulasinya)</div>
            <div class="checklist-answer {{ $hraHotWork->materials_other_side_removed ? 'yes' : 'no' }}">
                {{ $hraHotWork->materials_other_side_removed ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">7. Atmosfer yang mudah meledak dihilangkan</div>
            <div class="checklist-answer {{ $hraHotWork->explosive_atmosphere_removed ? 'yes' : 'no' }}">
                {{ $hraHotWork->explosive_atmosphere_removed ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">8. Semua bukaan dinding/lantai, termasuk saluran pembuangan, ditutup dengan penutup tahan api</div>
            <div class="checklist-answer {{ $hraHotWork->wall_floor_openings_covered ? 'yes' : 'no' }}">
                {{ $hraHotWork->wall_floor_openings_covered ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">9. Saluran, konveyor, katup/saluran pembuangan yang terbuka secara otomatis, dll, terlindungi, terisolasi, atau keduanya</div>
            <div class="checklist-answer {{ $hraHotWork->ducts_conveyors_protected ? 'yes' : 'no' }}">
                {{ $hraHotWork->ducts_conveyors_protected ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">10. Jika ada risiko kebakaran dari konduksi/radiasi, misalnya di sepanjang balok, tindakan pencegahan tambahan diterapkan</div>
            <div class="checklist-answer {{ $hraHotWork->fire_risk_prevention_applied ? 'yes' : 'no' }}">
                {{ $hraHotWork->fire_risk_prevention_applied ? 'V' : '' }}
            </div>
        </div>

        <!-- Section 2: Requirements when working on enclosed equipment -->
        <div style="font-weight: bold; font-size: 12px; margin: 10px 0; background-color: #e8f4f8; padding: 5px; border-left: 4px solid #007bff;">
            Bagian 2: Persyaratan saat bekerja pada peralatan tertutup
        </div>

        <div class="checklist-item">
            <div class="checklist-question">11. Peralatan dibersihkan dari semua bahan yang mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->equipment_cleaned_flammable ? 'yes' : 'no' }}">
                {{ $hraHotWork->equipment_cleaned_flammable ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">12. Wadah dikosongkan, dibersihkan, dan diuji bebas dari cairan dan uap yang mudah terbakar (Lengkapi form-H)</div>
            <div class="checklist-answer {{ $hraHotWork->containers_emptied_cleaned ? 'yes' : 'no' }}">
                {{ $hraHotWork->containers_emptied_cleaned ? 'V' : '' }}
            </div>
        </div>

        <!-- Section 3: Building panels/materials -->
        <div style="font-weight: bold; font-size: 12px; margin: 10px 0; background-color: #e8f4f8; padding: 5px; border-left: 4px solid #007bff;">
            Bagian 3: Panel bangunan/material
        </div>

        <div class="checklist-item">
            <div class="checklist-question">13. Panel bangunan/material yang sedang dikerjakan adalah diketahui tidak mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->building_materials_non_flammable ? 'yes' : 'no' }}">
                {{ $hraHotWork->building_materials_non_flammable ? 'V' : '' }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">14. Jika "tidak" pada pertanyaan di atas, bahan yang mudah terbakar HARUS dipotong hingga minimal 50 cm dan dilindungi oleh bahan pelindung yang tidak mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_materials_cut_protected ? 'yes' : 'no' }}">
                {{ $hraHotWork->flammable_materials_cut_protected ? 'V' : '' }}
            </div>
        </div>

        <!-- Section 4: Ventilation -->
        <div style="font-weight: bold; font-size: 12px; margin: 10px 0; background-color: #e8f4f8; padding: 5px; border-left: 4px solid #007bff;">
            Bagian 4: Ventilasi
        </div>

        <div class="checklist-item">
            <div class="checklist-question">15. Ventilasi yang cukup di tempat kerja 
                @if($hraHotWork->ventilation_type)
                    ({{ $hraHotWork->ventilation_type }})
                @endif
            </div>
            <div class="checklist-answer {{ $hraHotWork->ventilation_adequate ? 'yes' : 'no' }}">
                {{ $hraHotWork->ventilation_adequate ? 'V' : '' }}
            </div>
        </div>

        <!-- Section 5: Blow lamps and gas cylinders -->
        <div style="font-weight: bold; font-size: 12px; margin: 10px 0; background-color: #e8f4f8; padding: 5px; border-left: 4px solid #007bff;">
            Bagian 5: Lampu tiup dan tabung gas
        </div>

        <div class="checklist-item">
            <div class="checklist-question">16. Lampu tiup dan tabung gas hanya boleh dipasang atau diganti di area terbuka dan berventilasi baik</div>
            <div class="checklist-answer {{ $hraHotWork->gas_lamps_open_area ? 'yes' : 'no' }}">
                {{ $hraHotWork->gas_lamps_open_area ? 'V' : '' }}
            </div>
        </div>

        <!-- Section 6: Equipment and welding -->
        <div style="font-weight: bold; font-size: 12px; margin: 10px 0; background-color: #e8f4f8; padding: 5px; border-left: 4px solid #007bff;">
            Bagian 6: Peralatan dan pengelasan
        </div>

        <div class="checklist-item">
            <div class="checklist-question">17. Apakah semua peralatan telah dipasang dan pengalasan dimonitor dari pengelasan dalam kondisi baik?</div>
            <div class="checklist-answer {{ $hraHotWork->equipment_installed_monitored ? 'yes' : 'no' }}">
                {{ $hraHotWork->equipment_installed_monitored ? 'V' : '' }}
            </div>
        </div>

        <!-- Section 7: Worker notification -->
        <div style="font-weight: bold; font-size: 12px; margin: 10px 0; background-color: #e8f4f8; padding: 5px; border-left: 4px solid #007bff;">
            Bagian 7: Pemberitahuan pekerja
        </div>

        <div class="checklist-item">
            <div class="checklist-question">18. Semua pekerja yang ada di area tersebut diberitahu tentang pekerjaan panas yang sedang dilakukan</div>
            <div class="checklist-answer {{ $hraHotWork->workers_notified ? 'yes' : 'no' }}">
                {{ $hraHotWork->workers_notified ? 'V' : '' }}
            </div>
        </div>
    </div>

    <!-- Footer for page 1 -->
    <div style="text-align: center; margin-top: 20px; font-size: 10px; color: #666;">
        Generated on {{ date('d/m/Y H:i:s') }} | HRA Hot Work Assessment | Page 1 of 2
    </div>

    <!-- Fire Safety Equipment -->
    <div class="page-break">
        <!-- Mini header for new page -->
        <div style="text-align: center; margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
            <div style="font-size: 16px; font-weight: bold;">HRA HOT WORK ASSESSMENT</div>
            <div style="font-size: 12px; margin-top: 3px;">{{ $hraHotWork->hra_permit_number }} - Page 2</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">PERALATAN PEMADAM API</div>
        
        <div style="margin-bottom: 10px;">
            <strong>APAR:</strong>
        </div>
        <div class="fire-safety-grid">
            <div class="fire-safety-row">
                <div class="fire-safety-cell fire-safety-header">Air</div>
                <div class="fire-safety-cell fire-safety-header">Powder</div>
                <div class="fire-safety-cell fire-safety-header">CO2</div>
                <div class="fire-safety-cell fire-safety-header">Fire Blanket</div>
                <div class="fire-safety-cell fire-safety-header">Petugas Fire Watch</div>
            </div>
            <div class="fire-safety-row">
                <div class="fire-safety-cell {{ $hraHotWork->apar_air ? 'yes' : 'no' }}">
                    {{ $hraHotWork->apar_air ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->apar_powder ? 'yes' : 'no' }}">
                    {{ $hraHotWork->apar_powder ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->apar_co2 ? 'yes' : 'no' }}">
                    {{ $hraHotWork->apar_co2 ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->fire_blanket ? 'yes' : 'no' }}">
                    {{ $hraHotWork->fire_blanket ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->fire_watch_officer ? 'yes' : 'no' }}">
                    {{ $hraHotWork->fire_watch_officer ? 'YA' : 'TIDAK' }}
                </div>
            </div>
        </div>

        @if($hraHotWork->fire_watch_officer && $hraHotWork->fire_watch_name)
        <div style="margin-top: 10px;">
            <span class="label">Nama Petugas Fire Watch:</span>
            {{ $hraHotWork->fire_watch_name }}
        </div>
        @endif

        <!-- Monitoring Section -->
        <div style="margin-top: 15px;">
            <div style="font-weight: bold; font-size: 12px; margin-bottom: 10px;">Membutuhkan monitoring:</div>
            
            <div class="checklist-item">
                <div class="checklist-question">1. Bangunan terpasang sprinkler</div>
                <div class="checklist-answer {{ $hraHotWork->monitoring_sprinkler ? 'yes' : 'no' }}">
                    {{ $hraHotWork->monitoring_sprinkler ? 'YA' : 'TIDAK' }}
                </div>
            </div>

            <div class="checklist-item">
                <div class="checklist-question">2. Tidak ada combustible material (kayu, plastik, oli, dll) digunakan di atap dinding atau lantai konstruksi, termasuk insulasi.</div>
                <div class="checklist-answer {{ $hraHotWork->monitoring_combustible ? 'yes' : 'no' }}">
                    {{ $hraHotWork->monitoring_combustible ? 'YA' : 'TIDAK' }}
                </div>
            </div>

            <div class="checklist-item">
                <div class="checklist-question">3. Semua combustible material, termasuk cairan flammable, debu combustible, debu mengandung oli, minimal sejauh 11 m dari lokasi kerja.</div>
                <div class="checklist-answer {{ $hraHotWork->monitoring_distance ? 'yes' : 'no' }}">
                    {{ $hraHotWork->monitoring_distance ? 'YA' : 'TIDAK' }}
                </div>
            </div>

            @if($hraHotWork->emergency_call)
            <div style="margin-top: 10px;">
                <span class="label">Breakglass/emergency call terdekat:</span>
                {{ $hraHotWork->emergency_call }}
            </div>
            @endif

            <div class="checklist-item">
                <div class="checklist-question">Cek kondisi sistem sprinkler (jika tersedia)</div>
                <div class="checklist-answer {{ $hraHotWork->sprinkler_check ? 'yes' : 'no' }}">
                    {{ $hraHotWork->sprinkler_check ? 'YA' : 'TIDAK' }}
                </div>
            </div>

            <div class="checklist-item">
                <div class="checklist-question">Mematikan peralatan detektor api?</div>
                <div class="checklist-answer {{ $hraHotWork->detector_shutdown ? 'yes' : 'no' }}">
                    {{ $hraHotWork->detector_shutdown ? 'YA' : 'TIDAK' }}
                </div>
            </div>

            @if($hraHotWork->detector_shutdown)
            <div style="margin-left: 20px;">
                <div class="checklist-item">
                    <div class="checklist-question">Pemberitahuan ke SHE & Security dibutuhkan?</div>
                    <div class="checklist-answer {{ $hraHotWork->notification_required ? 'yes' : 'no' }}">
                        {{ $hraHotWork->notification_required ? 'YA' : 'TIDAK' }}
                    </div>
                </div>

                @if($hraHotWork->notification_required)
                <div style="margin-left: 20px;">
                    @if($hraHotWork->notification_phone)
                    <div class="row">
                        <span class="label">Phone:</span>
                        {{ $hraHotWork->notification_phone }}
                    </div>
                    @endif
                    @if($hraHotWork->notification_name)
                    <div class="row">
                        <span class="label">Contact Name:</span>
                        {{ $hraHotWork->notification_name }}
                    </div>
                    @endif
                </div>
                @endif

                <div class="checklist-item">
                    <div class="checklist-question">Pemberitahuan ke Asuransi dibutuhkan?</div>
                    <div class="checklist-answer {{ $hraHotWork->insurance_notification ? 'yes' : 'no' }}">
                        {{ $hraHotWork->insurance_notification ? 'YA' : 'TIDAK' }}
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">Memastikan detektor sudah mati?</div>
                    <div class="checklist-answer {{ $hraHotWork->detector_confirmed_off ? 'yes' : 'no' }}">
                        {{ $hraHotWork->detector_confirmed_off ? 'YA' : 'TIDAK' }}
                    </div>
                </div>
            </div>
            @endif

            <div class="checklist-item">
                <div class="checklist-question">Gas monitoring untuk kemungkinan gas flammable dibutuhkan selama bekerja?</div>
                <div class="checklist-answer {{ $hraHotWork->gas_monitoring_required ? 'yes' : 'no' }}">
                    {{ $hraHotWork->gas_monitoring_required ? 'YA' : 'TIDAK' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Section -->
    @if($hraHotWork->approval_status === 'approved')
    <div class="section approval-section">
        <div class="section-title approval-title">APPROVAL STATUS</div>
        <div class="two-column">
            <div class="column">
                <div class="row">
                    <span class="label">Location Owner:</span>
                    {{ $permit->locationOwner->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Approval Status:</span>
                    <span style="color: green; font-weight: bold;">{{ strtoupper($hraHotWork->area_owner_approval) }}</span>
                </div>
                <div class="row">
                    <span class="label">Approved By:</span>
                    {{ $hraHotWork->areaOwnerApprovedBy->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Approved At:</span>
                    {{ $hraHotWork->area_owner_approved_at ? $hraHotWork->area_owner_approved_at->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="label">EHS Team:</span>
                    EHS Department
                </div>
                <div class="row">
                    <span class="label">Approval Status:</span>
                    <span style="color: green; font-weight: bold;">{{ strtoupper($hraHotWork->ehs_approval) }}</span>
                </div>
                <div class="row">
                    <span class="label">Approved By:</span>
                    {{ $hraHotWork->ehsApprovedBy->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Approved At:</span>
                    {{ $hraHotWork->ehs_approved_at ? $hraHotWork->ehs_approved_at->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
        </div>
        <div style="margin-top: 10px; text-align: center; font-weight: bold; color: green;">
            FINAL APPROVAL: {{ $hraHotWork->final_approved_at ? $hraHotWork->final_approved_at->format('d/m/Y H:i') : 'N/A' }}
        </div>
    </div>
    @endif

    <!-- Main Permit Information -->
    <div class="section">
        <div class="section-title">MAIN PERMIT INFORMATION</div>
        <div style="display: table; width: 100%; margin-bottom: 10px;">
            <div style="display: table-cell; width: 75%; vertical-align: top; padding-right: 15px;">
                <div class="two-column">
                    <div class="column" style="padding-right: 15px;">
                        <div class="row">
                            <span class="label" style="width: 120px;">Permit Number:</span>
                            <span style="word-wrap: break-word; word-break: break-all; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->permit_number }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Work Title:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->work_title }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Department:</span>
                            {{ $permit->department }}
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Location:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->work_location }}</span>
                        </div>
                    </div>
                    <div class="column">
                        <div class="row">
                            <span class="label" style="width: 120px;">Permit Issuer:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->permitIssuer->name ?? 'N/A' }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Authorizer:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->authorizer->name ?? 'N/A' }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Receiver:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->receiver->name ?? 'N/A' }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Valid Period:</span>
                            <span style="white-space: nowrap;">{{ $permit->start_date->format('d/m/Y') }} - {{ $permit->end_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: table-cell; width: 25%; text-align: center; vertical-align: middle; border-left: 1px solid #ccc; padding-left: 15px;">
                @if(isset($permitQrCode))
                    <div style="font-weight: bold; font-size: 10px; margin-bottom: 8px; color: #666;">Main Permit QR</div>
                    <img src="data:image/svg+xml;base64,{{ $permitQrCode }}" alt="Main Permit QR Code" style="width: 80px; height: 80px; border: 1px solid #ddd;">
                @else
                    <div style="width: 80px; height: 80px; border: 2px solid #666; margin: 0 auto; text-align: center; font-size: 7px; padding: 5px; background: #f9f9f9;">
                        <div style="font-weight: bold; margin-bottom: 3px;">MAIN PERMIT</div>
                        <div style="font-size: 6px; word-wrap: break-word;">{{ $permit->permit_number }}</div>
                    </div>
                @endif
                                <div style="font-size: 8px; color: #666; margin-top: 5px;">Scan untuk detail permit utama</div>
            </div>
        </div>
    </div>

    <!-- Footer for page 2 -->
    <div style="text-align: center; margin-top: 20px; font-size: 10px; color: #666;">
        Generated on {{ date('d/m/Y H:i:s') }} | HRA Hot Work Assessment | Page 2 of 2
    </div>
</body>
</html>
            </div>
        </div>
    </div>


</body>
</html>