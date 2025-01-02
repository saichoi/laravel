<?php

use App\Models\Article;
use App\Models\User;

test('댓글을_작성할_수_있다', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();
    $payload = ['article_id' => $article->id, 'body' => 'hello'];
    $this->actingAs($user)
        ->post(route('comments.store'), $payload)
        ->assertStatus(302)
        ->assertRedirectToRoute('articles.show', ['article' => $article->id]);

    $this->assertDatabaseHas('comments', $payload);
});