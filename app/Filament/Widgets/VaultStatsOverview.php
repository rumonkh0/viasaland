<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Document;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VaultStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalFiles = Document::count();
        $pendingFiles = Document::where('status', 'pending')->count();
        $lockedFiles = Document::where('is_locked', true)->count();
        $totalBytes = (int) Document::sum('file_size');

        $formattedSize = $totalBytes >= 1048576
            ? number_format($totalBytes / 1048576, 2).' MB'
            : ($totalBytes >= 1024 ? number_format($totalBytes / 1024, 2).' KB' : $totalBytes.' B');

        $activeApps = Application::whereIn('status', ['submitted', 'under_review', 'verified'])->count();

        return [
            Stat::make('Total Vault Files', $totalFiles)
                ->description("{$activeApps} active visa applications")
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('primary'),

            Stat::make('Pending Review', $pendingFiles)
                ->description($pendingFiles > 0 ? 'Requires administrative action' : 'All clear')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingFiles > 0 ? 'warning' : 'success'),

            Stat::make('Locked Documents', $lockedFiles)
                ->description('Protected from user deletion')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('danger'),

            Stat::make('Vault Storage Used', $formattedSize)
                ->description('Total size of stored visa documents')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('info'),
        ];
    }
}
