<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name', 'discription', 'parent_id', 'slug', 'image', 'status'
    ];

    public function products(){
        return $this->hasMany(Product::class, 'category_id','id');
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_id','id')
        ->withDefault([
            'name' => 'main'
        ]);
    }

    public function children(){
        return $this->belongsTo(Category::class, 'parent_id','id');
    }

    public static function uploadImage(Request $request)
    {
        if (! $request->hasFile('image')) {
            return;
        }
        $file = $request->file('image');
        $path =  $file->store('uploads', 'public');
        return $path;
    }

    public function scopeFilter(Builder $builder,$filters){
        // $builder->when($filters['name'] ?? false ,function($builder,$value){
        //     $builder->where('name','LIKE',"%$value%");
        // });
        // $builder->when($filters['status'] ?? false ,function($builder,$value){
        //     $builder->where('status','=',"$value");
        // });

        if ($filters['name'] ?? false){
            $builder->where('categories.name','LIKE',"%{$filters['name']}%");
        }
        if ($filters['status'] ?? false){
            $builder->where('categories.status','=',"{$filters['status']}");
        }
    }

    public static function rules($id = 0)
    {
        return  [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categories', 'name')->ignore($id),
                //   the same as  "unique:categories,name,$id"
            ],

            'parent_id' => ['nullable', 'int', 'exists:categories,id'],
            'description' => ['string'],
            'image' => ['image', 'max:1048576', 'dimensions:min_width=100,min_height=100'],
            'status' => ['in:active,archived']
        ];
    }


    // protected $guarded =['id'];
}
