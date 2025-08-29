<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to projects index
Route::get('/', function () {
    return redirect()->route('projects.index');
});

// Resource routes
Route::resource('projects', ProjectController::class);
Route::resource('issues', IssueController::class);
Route::resource('tags', TagController::class);

// AJAX routes for tags
Route::post('issues/{issue}/tags/attach', [IssueController::class, 'attachTag'])->name('issues.tags.attach');
Route::delete('issues/{issue}/tags/detach', [IssueController::class, 'detachTag'])->name('issues.tags.detach');

// AJAX routes for user assignment
Route::post('issues/{issue}/users/assign', [IssueController::class, 'assignUser'])->name('issues.users.assign');
Route::delete('issues/{issue}/users/unassign', [IssueController::class, 'unassignUser'])->name('issues.users.unassign');

// AJAX routes for comments
Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
