<?php
require_once(__DIR__ . '/../middleware/Auth.class.php');
require_once(__DIR__ . '/../context/UserContext.php');

Flight::group('/comment', function () {
    /**
     * @OA\Post(
     *      path="/comment",
     *      tags={"comments"},
     *      summary="Add a comment",
     *      @OA\Response(
     *           response=200,
     *           description="Adding Comment"
     *      ),
     *      @OA\RequestBody(
     *          description="Comment Body",
     *          @OA\JsonContent(
     *             required={"content", "parentId"},
     *             @OA\Property(property="content", required=true, type="string", example="Hello, world"),
     *             @OA\Property(property="parentId", required=true, type="number", example=1)
     *           )
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('POST /', function () {
        $data = json_decode(Flight::request()->getBody(), true);
        $data['createdBy'] = User::id();
        return Flight::commentService()->create($data);
    });


    /**
     * @OA\Patch(
     *      path="/comment",
     *      tags={"comments"},
     *      summary="Add a comment",
     *      @OA\Response(
     *           response=200,
     *           description="Updating Comment"
     *      ),
     *      @OA\RequestBody(
     *          description="Comment Body",
     *          @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", required=true, type="string", example="Updated Hello, World")
     *           )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Comment ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('PATCH /', function () {
        $data = json_decode(Flight::request()->getBody(), true);
        $id = Flight::request()->query['id'];
        return Flight::commentService()->update($id, ['content' => $data['content']]);
    });

    /**
     * @OA\Get(
     *      path="/comment",
     *      tags={"comments"},
     *      summary="Get a comment",
     *      @OA\Response(
     *           response=200,
     *           description="Got a Comment"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Comment ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('GET /', function () {
        $id = Flight::request()->query['id'];
        return Flight::commentService()->get($id);
    });


    /**
     * @OA\Get(
     *      path="/comment/all",
     *      tags={"comments"},
     *      summary="Get comments of a post",
     *      @OA\Response(
     *           response=200,
     *           description="Got comments"
     *      ),
     *      @OA\Parameter(
     *          name="postId",
     *          in="query",
     *          description="Post ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('GET /all', function () {

        $params = ["postId" => Flight::request()->query['postId']];
        if (isset(Flight::request()->query['page'])) {
            $params['page'] = Flight::request()->query['page'];
        }

        if (isset(Flight::request()->query['size'])) {
            $params['limit'] = Flight::request()->query['size'];
        }
        return Flight::commentService()->get_all_by_post(...$params);

    });
    /**
     * @OA\Delete(
     *      path="/comment",
     *      tags={"comments"},
     *      summary="Delete a comment",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted the Comment"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Comment ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('DELETE /', function () {
        $id = Flight::request()->query['id'];
        return Flight::commentService()->delete($id);
    });

    /**
     * @OA\Patch(
     *      path="/comment/like",
     *      tags={"comments"},
     *      summary="Like a comment",
     *      @OA\Response(
     *           response=200,
     *           description="Liked the Comment"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Comment ID",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    Flight::route('PATCH /like', function () {
        $id = Flight::request()->query['id'];
        return Flight::commentService()->handleLike($id);
    });

}, [new Auth()]);