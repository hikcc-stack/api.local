<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameController extends Controller
{
    // Использование ресурсов GameResource
    public function index()
    {
        $games = Game::all();
        return GameResource::collection($games); // Laravel сам обернет коллекцию внутрь data
    }

    /*
    // Получение всех игр
    public function index(){
        $games = Game::all();
        return response()->json([
            'status' => 'success',
            'data' => $games
        ], 200);
    }
    */
    // использование ресурса GameResource
    public function show($id)
    {
        $game = Game::find($id);

        // Использование общего класса ApiException для обработки ошибки
        if (!$game) {
            throw new ApiException('Игра не найдена');
        }
        /*
        if (!$game) {
            return response()->json([
                'status' => 404,
                'message' => 'Game not found'
            ], 404);
        }
        */
        return new GameResource($game); // Одиночный ресурс
    }
    /*
    // Получение одной игры по id
    // Добавляем передаваемое значение id
    public function show($id){
        // Ищем игру по её идентификатору
        $game = Game::find($id);

        // Если игра не найдена - ответ в формате JSON - игра не найдена
        if(!$game){
            return response()->json([
                'status' => 'Not found',
                'message' => 'Game not found'
            ], 404);
        }
        // Если найдена - выводим игру, с дополнительным сообщением status - success
        return response()->json([
            'status' => 'success',
            'data' => $game
        ], 200);
    }
    */
    // использование ресурса GameResource
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'gamepad' => 'boolean',
        ]);
        $game = Game::create($validated);
        return new GameResource($game)->response()->setStatusCode(201);
    }
    /*
    // Создание новой игры
    public function store(Request $request){
        // Валидация входящих данных
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'gamepad' => 'boolean',
        ]);
        // Создаем игру и валидируем её
        $game = Game::create($validated);
        return response()->json([
           'status' => 'success',
           'message' => 'Game created',
            'data' => $game
        ], 201); // 201 - Created
    }
    */
    // использование ресурса GameResource
    public function update(Request $request, $id){
        $game = Game::find($id);

        if (!$game) {
            return response()->json([
                'status' => 'error',
                'message' => 'Game not found'
            ], 404);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'gamepad' => 'boolean',
        ]);
        $game->update($validated);
        return new GameResource($game);
    }
    /*
    // Обновление игры
    public function update(Request $request, $id){
        $game = Game::find($id);

        if(!$game){
            return response()->json([
                'status' => 'Not found',
                'message' => 'Game not found'
            ], 404);
        }
        // Валидация правил sometimes - означает, что поле необязательно, но если оно пришло его необходимо проверить
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'genre' => 'sometimes|required|string|max:255',
            'gamepad' => 'boolean',
        ]);

        $game->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Game updated',
            'data' => $game
        ], 200);
    }
    */


    // Удаление игры
    public function destroy($id){
        $game=Game::find($id);

        if(!$game){
            return response()->json([
                'status' => 'Not found',
                'message' => 'Game not found'
            ], 404);
        }
        $game->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Game deleted'
        ], 200);
    }

}
