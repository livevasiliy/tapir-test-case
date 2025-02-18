<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VehicleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => VehicleResource::collection($this->collection),
            'pagination' => $this->paginationInformation($request),
        ];
    }

    /**
     * Customize the pagination information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    protected function paginationInformation($request): array
    {
        $paginated = $this->resource; // Paginator instance

        return [
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
            'path' => $paginated->path(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
            'from' => $paginated->firstItem(), // First item on the current page
            'to' => $paginated->lastItem(),   // Last item on the current page
        ];
    }
}
