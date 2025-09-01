<?php

namespace App\Observers;

use App\Models\InfrastructureReport;

class InfrastructureReportObserver
{
    /**
     * Handle the InfrastructureReport "created" event.
     */
    public function created(InfrastructureReport $infrastructureReport): void
    {
        //
    }

    /**
     * Handle the InfrastructureReport "updated" event.
     */
    public function updated(InfrastructureReport $infrastructureReport): void
    {
        //
    }

    /**
     * Handle the InfrastructureReport "deleted" event.
     */
    public function deleted(InfrastructureReport $infrastructureReport): void
    {
        //
    }

    /**
     * Handle the InfrastructureReport "restored" event.
     */
    public function restored(InfrastructureReport $infrastructureReport): void
    {
        //
    }

    /**
     * Handle the InfrastructureReport "force deleted" event.
     */
    public function forceDeleted(InfrastructureReport $infrastructureReport): void
    {
        //
    }
}
