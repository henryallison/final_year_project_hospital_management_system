<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Phpml\DecisionTree\DecisionTree;

class AIAccessControl
{
    public function handle($request, Closure $next, $resourceType)
    {
        $user = Auth::user();

        // AI Decision Tree for access control
        $samples = [
            [$user->role_id, $user->department, $user->clearance_level],
            // Training data...
        ];
        $labels = ['granted', 'denied']; // Training labels

        $classifier = new DecisionTree();
        $classifier->train($samples, $labels);

        $decision = $classifier->predict([
            $user->role_id,
            $request->resource_type,
            $request->sensitivity_level
        ]);

        if ($decision === 'denied') {
            abort(403, 'AI Access Control: Permission Denied');
        }

        return $next($request);
    }
}
