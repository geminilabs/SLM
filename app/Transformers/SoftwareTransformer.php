<?php

namespace App\Transformers;

use App\Software;
use League\Fractal\TransformerAbstract;

class SoftwareTransformer extends TransformerAbstract
{
	public function transform( Software $software )
	{
		return [
			'archivedAt' => (string) $software->deleted_at,
			'createdAt' => (string) $software->created_at,
			'name' => (string) $software->name,
			'repository' => (string) $software->repository,
			'slug' => (string) $software->slug,
			'status' => (string) $software->status,
			'updatedAt' => (string) $software->updated_at,
		];
	}
}
