<?php
require_once(__DIR__ . '/../middleware/Auth.class.php');
require_once(__DIR__ . '/../context/UserContext.php');

Flight::group('/post', function () {
    /**
     * @OA\Post(
     *      path="/post",
     *      tags={"posts"},
     *      summary="Add a post",
     *      @OA\Response(
     *           response=200,
     *           description="Added Comment"
     *      ),
     *      @OA\RequestBody(
     *          description="Post Body",
     *          @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", required=true, type="string", example="Hello, World")
     *           )
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('POST /', function () {
        $data = json_decode(Flight::request()->getBody(), true);
        $data['createdBy'] = User::id();
        return Flight::postService()->create($data);
    });

    /**
     * @OA\Patch(
     *      path="/post",
     *      tags={"posts"},
     *      summary="Update a post",
     *      @OA\Response(
     *           response=200,
     *           description="Updating Post"
     *      ),
     *      @OA\RequestBody(
     *          description="Post Body",
     *          @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", required=true, type="string", example="Updated Hello, World")
     *           )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Post ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('PATCH /', function (){
        $data = json_decode(Flight::request()->getBody(), true);
        $id = Flight::request()->query['id'];
        return Flight::postService()->update($id,['content'=> $data['content']]);
    });

    /**
     * @OA\Get(
     *      path="/post",
     *      tags={"posts"},
     *      summary="Get a post",
     *      @OA\Response(
     *           response=200,
     *           description="Got the Post"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Post ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('GET /', function (){
        $id = Flight::request()->query['id'];
        return Flight::postService()->get($id);
    });


    /**
     * @OA\Get(
     *      path="/post.all",
     *      tags={"posts"},
     *      summary="Get all posts",
     *      @OA\Response(
     *           response=200,
     *           description="Got all Posts"
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('GET /all', function (){

        $params = [];
        if (isset(Flight::request()->query['page'])) {
            $params['page'] = Flight::request()->query['page'];
        }

        if (isset(Flight::request()->query['size'])) {
            $params['limit'] = Flight::request()->query['size'];
        }
        return Flight::postService()->get_all(...$params);

    });

    /**
     * @OA\Delete(
     *      path="/post",
     *      tags={"posts"},
     *      summary="Delete a post",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted the Post"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Post ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('DELETE /', function (){
        $id = Flight::request()->query['id'];
        return Flight::postService()->delete($id);
    });

    /**
     * @OA\Patch(
     *      path="/post/like",
     *      tags={"posts"},
     *      summary="Like a post",
     *      @OA\Response(
     *           response=200,
     *           description="Liked the Post"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Post ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('PATCH /like', function (){
        $id = Flight::request()->query['id'];
        return Flight::postService()->handleLike($id);
    });

}, [new Auth()]);