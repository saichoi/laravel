<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Carbon;

test('로그인_한_사용자는_글쓰기_화면을_볼_수_있다', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('articles.create'))
        ->assertStatus(200)
        ->assertSee('글쓰기');
});

test('로그인_하지_않은_사용자는_글쓰기_화면을_볼_수_없다', function () {
    $this->get(route('articles.create'))
        ->assertStatus(302)
        ->assertRedirectToRoute('login');
}); 

test('로그인_한_사용자는_글을_작성할_수_있다', function() {
    
    $testData = [
        'body' => 'test article'
    ];
    $user = User::factory()->create();

    // 어떤 API 실행되었는지 어떤 페이지로 리다이렉트 되었는지 확인
    $this->actingAs($user)->post(route('articles.store'), $testData)->assertRedirect(route('articles.index'));

    // 디비에 저장되었는지 확인
    $this->assertDatabaseHas('articles', $testData);
});

test('로그인_하지_않은_사용자는_글을_작성할_수_없다', function() {
    
    $testData = [
        'body' => 'test article'
    ];

    // 어떤 API 실행되었는지 어떤 페이지로 리다이렉트 되었는지 확인
    $this->post(route('articles.store'), $testData)->assertRedirectToRoute('login');

    // 디비에 저장되었는지 확인
    $this->assertDatabaseMissing('articles', $testData);
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

test('로그인_한_사용자는_글쓰기_수정 화면을_볼_수_있다', function () {
    $user = User::factory()->create();

    $article = Article::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('articles.edit', ['article' => $article->id]))
        ->assertStatus(200)
        ->assertSee('글 수정하기');
});

test('로그인_하지_않은_사용자는_글쓰기_수정 화면을_볼_수_없다', function () {
    $article = Article::factory()->create();

    $this->get(route('articles.edit', ['article' => $article->id]))
        ->assertRedirectToRoute('login');
});

test('로그인_한_사용자는_글을_수정할_수_있다', function() {
    $user = User::factory()->create();

    $payload =  ['body' => '수정된 글'];
    
    $article = Article::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->patch(route('articles.update', ['article' => $article->id]), $payload)
        ->assertRedirect(route('articles.index'));

    // $this->assertDatabaseHas('articles', $payload);
    $this->assertEquals($payload['body'], $article->refresh()->body);
});

test('로그인_하지_않은_사용자는_글을_수정할_수_없다', function() {
    $payload =  ['body' => '수정된 글'];
    
    $article = Article::factory()->create();

    $this->patch(route('articles.update', ['article' => $article->id]), $payload)
        ->assertRedirectToRoute('login');

    // $this->assertDatabaseMissing('articles', $payload);
    $this->assertNotEquals($payload['body'], $article->refresh()->body);
});

test('로그인_한_사용자는_글을_삭제할_수_있다', function() {
    $user = User::factory()->create();

    $article = Article::factory()->create(['user_id' => $user->id]);
    
    $this->actingAs($user)
        ->delete(route('articles.destroy', ['article' => $article->id]))
        ->assertRedirect(route('articles.index'));

    $this->assertDatabaseMissing('articles', ['id' => $article->id]);
});

test('로그인_하지_않은_사용자는_글을_삭제할_수_없다', function() {
    $article = Article::factory()->create();
    
    $this->delete(route('articles.destroy', ['article' => $article->id]))
        ->assertRedirectToRoute('login');

    $this->assertDatabaseHas('articles', ['id' => $article->id]);
});