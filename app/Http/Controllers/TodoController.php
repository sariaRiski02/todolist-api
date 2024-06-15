<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TodosResources;
use App\Http\Requests\CreateTodoRequest;

use App\Http\Requests\TodoUpdateRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TodoController extends Controller
{


    public function DataNotFound()
    {
        return response()->json([
            "errors" => [
                "message" => "Todo not found"
            ]
        ], 404);
    }


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

    public function getAllbyUser()
    {
        $user_id = Auth::user()->id;
        $todos = Todo::where('id_user', $user_id)->get();
        return  TodosResources::collection($todos);
    }

    public function getById($id)
    {
        $current_user = Auth::user();
        $todo = Todo::where('id_user', $current_user->id);
        if (!$todo) {
            return  $this->DataNotFound();
        }
        if (in_array($id, $todo->pluck('id')->toArray())) {
            $todo = $todo->where('id', $id)->first();
            return new TodosResources($todo);
        } else {
            return  $this->DataNotFound();
        }
    }

    public function update(TodoUpdateRequest $request, $id)
    {

        $data = $request->validated();
        $current_user = Auth::user();
        $todo = Todo::where('id_user', $current_user->id);
        if (!$todo) {
            return  $this->DataNotFound();
        }
        if (in_array($id, $todo->pluck('id')->toArray())) {

            $todo = $todo->where('id', $id)->first();

            if (isset($data->title)) {
                $todo->title = $data->title;
            }
            if (isset($data->description)) {
                $todo->description = $data->description;
            }
            if (isset($data->completed)) {
                $todo->completed = $data->completed;
            }

            $todo->save();


            return new TodosResources($todo);
        } else {
            return  $this->DataNotFound();
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $todo = $user->todos()->where('id', $id)->first();
        }
        if ($todo === null) {
            return $this->DataNotFound();
        }
        $result = $todo->delete();
        return $result ?? false;
    }


    public function search(Request $request)
    {
        $user = Auth::user();
        $todo = Todo::where('id_user', $user->id);

        $search = $request->input('search');
        $todos = $todo->whereAny(['title', 'description'], "LIKE", "%$search%")->get();
        if ($todos->isEmpty()) {
            return $this->DataNotFound();
        }

        return TodosResources::collection($todos);
    }
}
