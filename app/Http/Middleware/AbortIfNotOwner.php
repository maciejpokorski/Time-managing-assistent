<?php

namespace App\Http\Middleware;

use Closure;
use App\Category;

class AbortIfNotOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $resourceName)
    {

        $objectName = ucfirst('App\\'.$resourceName);
        $id = $request->route()->parameters[$resourceName];
        $object = new $objectName;
        $object = $object->findOrFail($id);
        $owner = $object->user()->get()->first()->id;
        
        if ($request->user()->id != $owner) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
