<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "Id"        =>  $this->id,
            "User Id"       =>  $this->user_id,
            "Phone"     =>  $this->phone,
            "Address"       =>  $this->address,
            "Date Of Birth"     =>  $this->date_of_birth,
            "Blog"       =>  $this->bio,
            "dimage"     =>  asset('/storage/images/' . $this->image),
            "Created At"        =>  $this->created_at->format('Y-m-d'),
            // "updated_at"        =>  $this->updated_at,
        ];
    }
}


// =========================
// return [
        //     "Id"        =>  $this->id,
        //     "User Id"       =>  $this->user_id,
        //     "Phone"     =>  $this->phone,
        //     "Address"       =>  $this->address,
        //     "Date Of Birth"     =>  $this->date_of_birth,
        //     "Blog"       =>  $this->bio,
        //     // "dimage"     =>  asset('/storage/images/' . $this->image),
        //     "dimage"     =>  asset('/storage/images/' . $this->image),
        //     "Created At"        =>  $this->created_at->format('Y-m-d'),
        //     // "image"     =>  $this->image,
        //     // "created_at"        =>  $this->created_at,
        //     // "updated_at"        =>  $this->updated_at,
        // ];