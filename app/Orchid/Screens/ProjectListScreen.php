<?php

namespace App\Orchid\Screens;

use App\Models\Event;
use App\Models\Project;
use App\Models\SubmittedBarcode;
use App\Orchid\Filters\ScansFilter;
use App\Orchid\Layouts\ScansFiltersLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ProjectListScreen extends Screen
{
    public $projects;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $projects = Project::orderBy('id', 'desc')->paginate();
        $projects->load('attachment');
        $projects->load('attachments');

//        var_dump();
//        die();

        return [
            'projects' => $projects
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Projects';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->href(route('platform.project.new')),
        ];
    }


    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
//        var_dump(($this->projects[0]->attachment()->latest()->first()->relative_url));
//        die();

        return [
            Layout::table('projects', [
                TD::make('id', 'ID')
                    ->width('100')
                    ->render(fn (Project $model) => // Please use view('path')
//                    "<img src='https://loremflickr.com/500/300?random={$model->get('id')}'
//                              alt='sample'
//                              class='mw-100 d-block img-fluid rounded-1 w-100'>
                            "<span class='small text-muted mt-1 mb-0'># $model->id</span>"),
                TD::make('title', 'Title')->render(fn (Project $model) =>
                        Link::make($model->title)
                        ->href(route('platform.project.edit', ["project" => $model->id]))),
//                TD::make('model_file', 'Model'),

                TD::make('model_file', 'Model')->render(function (Project $model) {
                    $url = $model->attachments()->latest()->first() ? preg_replace('#^' . preg_quote('/1') . '#', '', $model->attachments()->latest()->first()->relative_url) : "";
                    return $model->attachments()->latest()->first() ? "<a href='{$url}'>{$model->attachment()->latest()->first()->original_name}</a>" : "";
                }),

//                TD::make('value', 'Value'),


                TD::make('updated_timezone_date', 'Created')
                    ->usingComponent(DateTimeSplit::class, timeZone: 'Europe/Madrid')
                    ->align(TD::ALIGN_RIGHT),

            ]),
        ];
    }
}
