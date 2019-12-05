<?php
// [GALLERY] Seeder alimentation tables
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Group;
use App\Models\Image;
use App\Models\Galerie;
use App\Models\Comment;
use App\Models\Like;
use App\Models\GalerieGroup;
use App\Models\GroupUser;

class GalleryTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Group::class, 3)->create();

        factory(User::class, 10)->create()->each(function ($user) {
            $pivotGroup = new GroupUser();
            $pivotGroup->user_id = $user->id;
            $pivotGroup->group_id = Group::all()->random()->id;
            $pivotGroup->save();
        });

        factory(Galerie::class, 6)->create()->each(function ($galerie) {
            $pivotGroup = new GalerieGroup();
            $pivotGroup->galerie_id = $galerie->id;
            $pivotGroup->group_id = Group::all()->random()->id;
            $pivotGroup->save();
        });

        factory(Image::class, 30)->create()->each(function ($image) {
            $faker = Faker\Factory::create();
            $nbLike = $faker->numberBetween(0, User::all()->count());
            for($indLike=0; $indLike < $nbLike; $indLike++) {
                $like = new Like();
                $like->image_id = $image->id;
                $like->user_id = User::all()->random()->id;
                $like->save();
            }
        });
        factory(Comment::class, 60)->create()->each(function ($comment) {
            $faker = Faker\Factory::create();
            $nbLike = $faker->numberBetween(0, User::all()->count());
            for($indLike=0; $indLike < $nbLike; $indLike++) {
                $like = new Like();
                $like->comment_id = $comment->id;
                $like->user_id = User::all()->random()->id;
                $like->save();
            }
        });
    }
}
