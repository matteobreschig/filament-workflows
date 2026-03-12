<?php

namespace Monzer\FilamentWorkflows\Resources\WorkflowResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use Monzer\FilamentWorkflows\Resources\WorkflowResource;
use Monzer\FilamentWorkflows\WorkflowsPlugin;

class CreateWorkflow extends CreateRecord
{
    protected static string $resource = WorkflowResource::class;

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }

    public static function canAccess(array $parameters = []): bool
    {
        return filament(app(WorkflowsPlugin::class)->getId())->isAuthorized();
    }
}
