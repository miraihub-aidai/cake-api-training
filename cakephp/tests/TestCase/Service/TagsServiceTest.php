<?php
declare(strict_types=1);

namespace App\Test\TestCase\Service;

use App\Model\Table\TagsTable;
use App\Service\TagsService;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\ResultSet;
use Cake\TestSuite\TestCase;
use RuntimeException;

class TagsServiceTest extends TestCase
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
     * Test getTags method
     *
     * @return void
     */
    public function testGetTags(): void
    {
        // ResultSetのモックを作成
        /** @var ResultSet&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $resultSetMock->method('toArray')
            ->willReturn($this->mockedTags);

        // SelectQueryのモックを作成
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willReturn($resultSetMock);

        // TagsTableのモックを作成
        /** @var TagsTable&\PHPUnit\Framework\MockObject\MockObject $tagsTableMock */
        $tagsTableMock = $this->getMockBuilder(TagsTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $tagsTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new TagsService($tagsTableMock);

        // メソッドを実行
        $result = $service->getTags();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertEquals($this->mockedTags, $result['tags']);
    }

    /**
     * Test getTags method with empty result
     *
     * @return void
     */
    public function testGetTagsEmpty(): void
    {
        // ResultSetのモックを作成（空の配列を返す）
        /** @var ResultSet&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $resultSetMock->method('toArray')
            ->willReturn([]);

        // SelectQueryのモックを作成
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willReturn($resultSetMock);

        // TagsTableのモックを作成
        /** @var TagsTable&\PHPUnit\Framework\MockObject\MockObject $tagsTableMock */
        $tagsTableMock = $this->getMockBuilder(TagsTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $tagsTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new TagsService($tagsTableMock);

        // メソッドを実行
        $result = $service->getTags();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertEmpty($result['tags']);
    }

    /**
     * Test getTagsById method
     *
     * @return void
     */
    public function testGetTagsById(): void
    {
        // ResultSetのモックを作成
        /** @var ResultSet<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($this->mockedTags[0]);

        $resultSetMock->method('first')
            ->willReturn($entityMock);

        // SelectQueryのモックを作成
        /** @var SelectQuery<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'where'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('where')
            ->with(['id' => 1])
            ->willReturn($resultSetMock);

        // TagsTableのモックを作成
        /** @var TagsTable&\PHPUnit\Framework\MockObject\MockObject $tagsTableMock */
        $tagsTableMock = $this->getMockBuilder(TagsTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $tagsTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new TagsService($tagsTableMock);

        // メソッドを実行
        $result = $service->getTagsById('1');

        // 結果を検証
        $this->assertEquals($this->mockedTags[0], $result);
    }

    /**
     * Test postTags method
     *
     * @return void
     */
    public function testPostTags(): void
    {
        // TagsTableのモックを作成
        /** @var TagsTable&\PHPUnit\Framework\MockObject\MockObject $tagsTableMock */
        $tagsTableMock = $this->getMockBuilder(TagsTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEntity', 'save'])
            ->getMock();

        $newTagData = [
            'title' => 'New Tag',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $savedTagData = $newTagData + ['id' => 1];

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($savedTagData);

        $tagsTableMock->method('newEntity')
            ->with($newTagData)
            ->willReturn($entityMock);

        $tagsTableMock->method('save')
            ->with($entityMock)
            ->willReturn($entityMock);

        // テスト対象のServiceを作成
        $service = new TagsService($tagsTableMock);

        // メソッドを実行
        $result = $service->postTags($newTagData);

        // 結果を検証
        $this->assertEquals($savedTagData, $result);
    }

    /**
     * Test putTags method
     *
     * @return void
     */
    public function testPutTags(): void
    {
        // TagsTableのモックを作成
        /** @var TagsTable&\PHPUnit\Framework\MockObject\MockObject $tagsTableMock */
        $tagsTableMock = $this->getMockBuilder(TagsTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($this->mockedTags[0]);

        $tagsTableMock->method('get')
            ->willReturn($entityMock);

        $tagsTableMock->method('patchEntity')
            ->willReturn($entityMock);

        $tagsTableMock->method('save')
            ->willReturn($entityMock);

        // テスト対象のServiceを作成
        $service = new TagsService($tagsTableMock);

        // メソッドを実行
        $result = $service->putTags('1', ['title' => 'Updated Tag']);

        // 結果を検証
        $this->assertEquals($this->mockedTags[0], $result);
    }

    /**
     * Test deleteTags method
     *
     * @return void
     */
    public function testDeleteTags(): void
    {
        // TagsTableのモックを作成
        /** @var TagsTable&\PHPUnit\Framework\MockObject\MockObject $tagsTableMock */
        $tagsTableMock = $this->getMockBuilder(TagsTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $tagsTableMock->method('get')
            ->willReturn($entityMock);

        $tagsTableMock->method('delete')
            ->willReturn(true);

        // テスト対象のServiceを作成
        $service = new TagsService($tagsTableMock);

        // メソッドを実行
        $result = $service->deleteTags('1');

        // 結果を検証
        $this->assertTrue($result);
    }

    /**
     * Test deleteTags method with failure
     *
     * @return void
     */
    public function testDeleteTagsFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to delete tag');

        // TagsTableのモックを作成
        /** @var TagsTable&\PHPUnit\Framework\MockObject\MockObject $tagsTableMock */
        $tagsTableMock = $this->getMockBuilder(TagsTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $tagsTableMock->method('get')
            ->willReturn($entityMock);

        $tagsTableMock->method('delete')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new TagsService($tagsTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->deleteTags('1');
    }
}
