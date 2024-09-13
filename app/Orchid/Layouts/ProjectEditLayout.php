<?php

namespace App\Orchid\Layouts;

use App\Models\Tag;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProjectEditLayout extends Rows
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = '';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function fields(): iterable
    {

        return [
            Input::make('project.title')
                ->required()
                ->title(__('Project Title'))
                ->placeholder(__('Project Title')),

//            Input::make('project.model_file')
//                ->type('file')
//                ->title('Model')
//                ->horizontal(),

            Upload::make('project.model_file')
                ->title('Model File')
                ->maxFiles(1)
                ->horizontal(),

//            Upload::make('project.image')
//                ->title('Image File')
//                ->maxFiles(1)
//                ->horizontal(),

            Input::make('project.value')
                ->required()
                ->title(__('Value'))
                ->placeholder(__('Value')),



        ];
    }
}
