<?php

namespace App\Http\Controllers;

use App\Http\Enums\PromptModelEnum;
use App\Http\Requests\Prompts\CreatePromptChainRequest;
use App\Http\Resources\Prompt\PromptResource;
use App\Services\PromptService;
use Inertia\Inertia;
use Inertia\Response;

class PromptController extends Controller
{
    public function __construct(private readonly PromptService $promptService) {}

    /**
     * @return Response
     */
    public function index(): Response
    {
        PromptResource::withoutWrapping();

        return Inertia::render('Prompts/Index', [
            'prompts' => PromptResource::collection($this->promptService->getPrompts()),
            'models'  => PromptModelEnum::cases(),
        ]);
    }

    /**
     * @param CreatePromptChainRequest $request
     * @return PromptResource
     */
    public function createChain(CreatePromptChainRequest $request): PromptResource
    {
        return PromptResource::make(
            $this->promptService->createChain($request->validated())->load(['chains'])
        );
    }
}
