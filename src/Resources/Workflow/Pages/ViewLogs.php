<?php

namespace Monzer\FilamentWorkflows\Resources\Workflow\Pages;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Monzer\FilamentWorkflows\WorkflowsPlugin;
use Monzer\FilamentWorkflows\Models\Workflow;
use Monzer\FilamentWorkflows\Resources\Workflow\WorkflowResource;
use Route;

class ViewLogs extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = WorkflowResource::class;

    protected string $view = 'filament-workflows::view-logs';

    public Workflow $record;
    public string   $logs, $execution_logs;

    public function getHeading(): string|Htmlable
    {
        return "Workflow #" . $this->record->id;
    }

    public function getSubheading(): string|Htmlable|null
    {
        $sub_heading = "#" . $this->record->description;
        $actions     = implode(', ', str_replace('-', ' ', $this->record->actions->pluck('action')->toArray()));
        return new HtmlString($sub_heading . "<br> <strong>$actions</strong");
    }


    public function mount()
    {
        $this->record  = Route::current()->parameter('record');
        $logs          = $this->record->logs ?? [];
        $executionLogs = $this->record->executions->pluck('logs')->flatten()->toArray();

        $logs          = array_filter($logs);
        $executionLogs = array_filter($executionLogs);

        $this->logs           = implode("\n\n", $logs);
        $this->execution_logs = implode("\n\n", $executionLogs);


        if (empty($this->logs)) {
            $this->logs = "No logs yet.";
        }

        if (empty($this->execution_logs)) {
            $this->execution_logs = "No logs yet.";
        }

    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Textarea::make('logs')
                            ->rows(10)
                            ->cols(10)
                            ->id('logs')
                            ->extraInputAttributes(['style' => 'color:#2a3ec5;'])
                            ->readOnly(),

                        Textarea::make('execution_logs')
                            ->rows(10)
                            ->cols(10)
                            ->id('execution_logs')
                            ->extraInputAttributes(['style' => 'color:#2a3ec5;'])
                            ->readOnly(),
                    ]),
            ]);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return filament(app(WorkflowsPlugin::class)->getId())->isAuthorized();
    }
}
