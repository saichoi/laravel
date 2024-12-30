<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Carbon;

test('글쓰기_화면을_볼_수_있다', function () {
    $this->get(route('articles.create'))
        ->assertStatus(200)
        ->assertSee('글쓰기');
});

test('글을_작성할_수_있다', function() {
    
    $testData = [
        'body' => 'test article'
    ];
    $user = User::factory()->create();

    // 어떤 API 실행되었는지 어떤 페이지로 리다이렉트 되었는지 확인
    $this->actingAs($user)->post(route('articles.store'), $testData)->assertRedirect(route('articles.index'));

    // 디비에 저장되었는지 확인
    $this->assertDatabaseHas('articles', $testData);
});

test('글_목록을_확인할_수_있다', function () {
     $now = Carbon::now();
     $afterOneSecond = (clone $now)->addSecond();

     $article1 = Article::factory()->create(
        ['created_at' => $now]
     );
     $article2 = Article::factory()->create(
        ['created_at' => $afterOneSecond]
     );

     $this->get(route('articles.index'))
        ->assertSeeInOrder([$article2->body, $article1->body]);
});

test('개별_글을_조회할_수_있다.', function() {
    $article = Article::factory()->create();

    $this->get(route('articles.show', ['article' => $article->id]))
        ->assertSuccessful()
        ->assertSee($article->body);
});

test('글쓰기_수정 화면을_볼_수_있다', function () {
    $article = Article::factory()->create();

    $this->get(route('articles.edit', ['article' => $article->id]))
        ->assertStatus(200)
        ->assertSee('글 수정하기');
});

test('글을_수정할_수_있다', function() {
    $payload =  ['body' => '수정된 글'];
    $article = Article::factory()->create();

    $this->patch(route('articles.update', ['article' => $article->id]), $payload)
        ->assertRedirect(route('articles.index'));

    // $this->assertDatabaseHas('articles', $payload);
    $this->assertEquals($payload['body'], $article->refresh()->body);
});

test('글을_삭제할_수_있다', function() {
    $article = Article::factory()->create();

    $this->delete(route('articles.delete', ['article' => $article->id]))
        ->assertRedirect(route('articles.index'));

    $this->assertDatabaseMissing('articles', ['id' => $article->id]);
});