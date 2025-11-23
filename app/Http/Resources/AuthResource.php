<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    protected string $accessToken;
    protected string $tokenType;

    /**
     * Set access token
     *
     * @param string $token
     * @return self
     */
    public function withToken(string $token, string $tokenType = 'Bearer'): self
    {
        $this->accessToken = $token;
        $this->tokenType = $tokenType;
        
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'user' => new UserResource($this->resource),
        ];

        if (isset($this->accessToken)) {
            $data['access_token'] = $this->accessToken;
            $data['token_type'] = $this->tokenType;
        }

        return $data;
    }
}
