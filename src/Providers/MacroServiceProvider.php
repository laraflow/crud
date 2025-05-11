<?php

namespace Laraflow\Crud\Providers;

use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        /**
         * return response with http 200 as deleted resource
         *
         * @param  $data
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('deleted',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_OK, $headers), Response::HTTP_OK, $headers);
            });

        /**
         * return response with http 200 as soft deleted resource restored
         *
         * @param  $data
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('restored',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_OK, $headers), Response::HTTP_OK, $headers);
            });

        /**
         * return response with http 201 resource created on server
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('created',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_CREATED, $headers), Response::HTTP_CREATED, $headers);
            });

        /**
         * return response with http 200 update request accepted
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('updated',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_OK, $headers), Response::HTTP_OK, $headers);
            });

        /**
         * return response with http 202 export request accepted
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('exported',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_ACCEPTED, $headers), Response::HTTP_ACCEPTED, $headers);
            });

        /**
         * return response with http 400 if business logic exception
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('failed',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_BAD_REQUEST, $headers), Response::HTTP_BAD_REQUEST, $headers);
            });

        /**
         * return response with http 500 if business logic exception
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('error',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_INTERNAL_SERVER_ERROR, $headers), Response::HTTP_INTERNAL_SERVER_ERROR, $headers);
            });

        /**
         * return response with http 200 for all success status
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('success',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_OK, $headers), Response::HTTP_OK, $headers);
            });

        /**
         * return response with http 401 if request token or ip banned
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('banned',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_UNAUTHORIZED, $headers), Response::HTTP_UNAUTHORIZED, $headers);
            });

        /**
         * return response with http 403 if access forbidden to that request
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('forbidden',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_FORBIDDEN, $headers), Response::HTTP_FORBIDDEN, $headers);
            });

        /**
         * return response with http 404 not found
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('notfound',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_NOT_FOUND, $headers), Response::HTTP_NOT_FOUND, $headers);
            });

        /**
         * return response with http 423 attempt locked
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('locked',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_LOCKED, $headers), Response::HTTP_LOCKED, $headers);
            });

        /**
         * return response with http 429 too many requests code
         *
         * @param  $content
         * @param  array  $headers
         * @return \Illuminate\Http\Response
         */
        ResponseFacade::macro('overflow',
            function ($content = '', array $headers = []) {
                return response(app('formatter')($content, Response::HTTP_TOO_MANY_REQUESTS, $headers), Response::HTTP_TOO_MANY_REQUESTS, $headers);
            });
    }
}
