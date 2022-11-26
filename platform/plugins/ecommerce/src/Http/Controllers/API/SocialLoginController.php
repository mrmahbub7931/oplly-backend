<?php

namespace Canopy\Ecommerce\Http\Controllers\API;

use Canopy\ACL\Traits\AuthenticatesUsers;
use Canopy\ACL\Traits\LogoutGuardTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Canopy\Media\RvMedia;

class SocialLoginController extends Controller
{

    use AuthenticatesUsers, LogoutGuardTrait;

    /**
     * Obtain the user information from {provider}.
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return JsonResponse
     */
    public function login(Request $request, BaseHttpResponse $response): JsonResponse
    {
        $input = $request->input();
        $account = app(CustomerInterface::class)->getFirstBy(['email' => $request->input('email')]);

        if (!$account) {
            $avatarId = null;
            try {
                $url = $request->input('picture');
                if ($url) {
                    $result = RvMedia::uploadFromUrl($url, 0, 'accounts', 'image/png');
                    if (!$result['error']) {
                        $avatarId = $result['data']->id;
                    }
                }
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
            Log::info('socialLoginPayload', $request->input());
            $account = app(CustomerInterface::class)->createOrUpdate([
                'name'        => $request->input('name'),
                'email'       => $request->input('email'),
                'verified_at' => now(),
                'password'    => bcrypt(Str::random(36)),
                'avatar_id'   => $avatarId,
            ]);
        }

        $token = $account->token() ?? $account->createToken('oplly')->accessToken;

        $authResponse = [
            "token_type"   => "Bearer",
            "access_token" => $token
        ];
        return response()->json($authResponse);
    }
}
