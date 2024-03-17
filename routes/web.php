<?php

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/tasks', function () {
    return view('index', [
        'tasks' => Task::latest()->paginate(10)
    ]);
})->name('tasks.index');

Route::view('/tasks/create', 'create')->name('tasks.create');

Route::get('/tasks/{task}/edit', function (Task $task) {
    return view('edit', ['task' => $task]);
})->name('tasks.edit');

Route::put('/tasks/{task}/toggle-completed', function (Task $task) {
    $task->toggleComplete();
    return redirect()->back()->with('success', 'Task update Successfully');
})->name('tasks.toggle-completed');

Route::put('/tasks/{task}', function (Task $task, TaskRequest $req) {
    $task->update($req->validated());
    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task Edit Successfully');
})->name('tasks.update');

Route::get('/tasks/{task}', function (Task $task) {
    return view('show', ['task' => $task]);
})->name('tasks.show');




Route::post('/tasks', function (TaskRequest $req) {

    $task = Task::create($req->validated());
    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task Created Successfully');
})->name('tasks.store');



Route::delete('/tasks/{task}', function (Task $task) {
    $task->delete();
    return redirect()->route('tasks.index')->with('success', 'Task Deleted Successfully');
})->name('tasks.destroy');



Route::fallback(
    function () {
        return 'Nothing to find here Dear';
    }
);
