<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\TagsInterface;
use App\Domain\UseCase\GetTags;
use Cake\TestSuite\TestCase;
use RuntimeException;

class GetTagsTest extends TestCase
{
    /**
     * @var array
     */
    protected array $mockedTags = [
        [
            'id' => 1,
            'title' => 'Test Tag 1',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'title' => 'Test Tag 2',
            'created' => '2024-01-02 00:00:00',
            'modified' => '2024-01-02 00:00:00',
        ],
    ];

    /**
     * Test invoke method
     *
     * @return void
     */
    public function testInvoke(): void
    {
        // TagsServiceのモックを作成
        /** @var TagsInterface&\PHPUnit\Framework\MockObject\MockObject $tagsServiceMock */
        $tagsServiceMock = $this->getMockBuilder(TagsInterface::class)
            ->getMock();

        // getTagsメソッドが1回呼ばれることを期待
        $tagsServiceMock->expects($this->once())
            ->method('getTags')
            ->willReturn(['tags' => $this->mockedTags]);

        // UseCaseを作成
        $useCase = new GetTags($tagsServiceMock);

        // UseCaseを実行
        $result = $useCase();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertEquals($this->mockedTags, $result['tags']);
    }

    /**
     * Test invoke method with empty result
     *
     * @return void
     */
    public function testInvokeEmpty(): void
    {
        // TagsServiceのモックを作成
        /** @var TagsInterface&\PHPUnit\Framework\MockObject\MockObject $tagsServiceMock */
        $tagsServiceMock = $this->getMockBuilder(TagsInterface::class)
            ->getMock();

        // getTagsメソッドが1回呼ばれ、空の配列を返すことを期待
        $tagsServiceMock->expects($this->once())
            ->method('getTags')
            ->willReturn(['tags' => []]);

        // UseCaseを作成
        $useCase = new GetTags($tagsServiceMock);

        // UseCaseを実行
        $result = $useCase();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertEmpty($result['tags']);
    }

    /**
     * Test invoke method with service error
     *
     * @return void
     */
    public function testInvokeServiceError(): void
    {
        // TagsServiceのモックを作成
        /** @var TagsInterface&\PHPUnit\Framework\MockObject\MockObject $tagsServiceMock */
        $tagsServiceMock = $this->getMockBuilder(TagsInterface::class)
            ->getMock();

        // getTagsメソッドが例外を投げることを期待
        $tagsServiceMock->expects($this->once())
            ->method('getTags')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new GetTags($tagsServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase();
    }
}
