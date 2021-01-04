<?php

namespace App\Http\Controllers;

use Mini\Routing\Attributes\Get;
use Mini\Routing\Attributes\Post;
use Mini\Routing\Attributes\Patch;
use Mini\Routing\Attributes\Put;
use Mini\Routing\Attributes\Delete;
use Mini\Routing\Attributes\Prefix;
use Mini\Routing\Attributes\Middleware;
use App\Http\Middleware\Authenticate;

#[
    Prefix('attribute'),
    Middleware('attribute')
]
class AttributeController extends Controller
{
    #[Get('get', 'auth')]
    public function get()
    {
        // GET http://xxxxxx/attribute/get?attribute=1&auth=跳过
        return '注解路由-GET';
    }

    #[Post('post', Authenticate::class)]
    public function post()
    {
        // POST http://xxxxxx/attribute/post?attribute=1&auth=跳过

        return '注解路由-POST';
    }

    #[Patch('patch')]
    public function patch()
    {
        // PATCH http://xxxxxx/attribute/patch?attribute=1

        return '注解路由-PATCH';
    }

    #[Put('put')]
    public function put()
    {
        // PUT http://xxxxxx/attribute/put?attribute=1

        return '注解路由-PUT';
    }

    #[Delete('delete', ['auth'])]
    public function delete()
    {
        // DELETE http://xxxxxx/attribute/delete?attribute=1&auth=跳过

        return '注解路由-DELETE';
    }

}