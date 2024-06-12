<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Resources\TodosResources;
use App\Http\Requests\CreateTodoRequest;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function create(CreateTodoRequest $request)
    {

        $user_id = Auth::user()->id;
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->id_user = $user_id;
        $todo->save();

        return new TodosResources($todo);
    }
}
