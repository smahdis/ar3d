<?php

namespace App\Orchid\Screens;

use App\Models\Branch;
use App\Models\Pilgrim;
use App\Models\Project;
use App\Orchid\Layouts\Branch\BranchEditLayout;
use App\Orchid\Layouts\Branch\BranchEditTranslationEnglishLayout;
use App\Orchid\Layouts\Branch\BranchEditTranslationGeorgianLayout;
use App\Orchid\Layouts\ProjectEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProjectEditScreen extends Screen
{

    /**
     * @var Project
     */
    public $project;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Project $project): iterable
    {
        $project->load('attachment');
        return [
            'project' => $project,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->project->exists ? __('Edit project') : __('Creating a new project');
    }


    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Create project'))
                ->icon('pencil')
                ->method('create')
                ->canSee(!$this->project->exists),

            Button::make(__('Update'))
                ->icon('note')
                ->method('update')
                ->canSee($this->project->exists),

            Button::make(__('Remove'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->project->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {

        return [
            Layout::block([
                ProjectEditLayout::class
            ])
                ->title(__('Project'))
//                ->description('These info are shown in branch\'s details dialog and they require translation.'),

        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function create(Request $request): RedirectResponse
    {

        $this->validate(request(), [
            'project.title' => 'required|max:255',
            'project.model_file' => 'required|max:255',

        ], [],
            [
                'project.title' => 'Title',
                'project.model_file'=> 'Model File',
            ]);

        $data = $request->get('project');
        $data['model_file'] = $data['model_file'][0];
//        var_dump($data);
//        die();

        if(empty($this->group)) {
            $this->project = new Project();
        }
        $this->project->fill($data)->save();

        $this->project->attachment()->syncWithoutDetaching(
            $request->input('project.model_file', [])
        );

        Alert::info('Project was created successfully');

        return redirect()->route('platform.project.list', ["project" =>  $this->project->id]);
    }


    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->validate(request(), [
            'project.title' => 'required|max:255',
            'project.model_file' => 'required|max:255',

        ], [],
            [
                'project.title' => 'عنوان',
                'project.model_file'=> 'فایل مدل',
            ]);

        $data = $request->get('project');
        $data['model_file'] = $data['model_file'][0];

        $project->fill($data)->save();
        $project->attachment()->syncWithoutDetaching(
            $request->input('project.model_file', [])
        );
        Alert::info('Project was created successfully');

        return redirect()->route('platform.project.list', ["project" =>  $this->project->id]);
    }

    /**
     * @param Project $project
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Project $project)
    {
//        var_dump($pilgrim->first_name);
//        die();

        $project_id = Route::getCurrentRoute()->project;
        Project::destroy($project->id);
        Alert::info('Project removed successfully');

        return redirect()->route('platform.project.list', ["project" =>  $project_id]);
    }

}
