<?php

namespace Tests\Feature;

use App\Enums\SchoolStatus;
use App\Models\Concerns\BelongsToSchool;
use App\Models\School;
use App\Models\Scopes\SchoolScope;
use Database\Seeders\SchoolSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchoolManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('test_tenant_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools');
            $table->string('title');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('test_tenant_records');
        app()->forgetInstance('current_school_id');

        parent::tearDown();
    }

    /**
     * Test school can be created using factory with valid attributes.
     */
    public function test_school_can_be_created_with_factory(): void
    {
        $school = School::factory()->create([
            'name' => 'Oakridge International Academy',
            'status' => SchoolStatus::Active,
        ]);

        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
            'name' => 'Oakridge International Academy',
            'status' => 'active',
        ]);
        $this->assertInstanceOf(SchoolStatus::class, $school->status);
        $this->assertSame(SchoolStatus::Active, $school->status);
        $this->assertSame('Active', $school->status->label());
    }

    /**
     * Test school slug is automatically derived from name if omitted.
     */
    public function test_school_slug_is_automatically_generated_if_omitted(): void
    {
        $school = School::create([
            'name' => 'St. Mary High School',
            'status' => SchoolStatus::Active,
        ]);

        $this->assertSame('st-mary-high-school', $school->slug);
    }

    /**
     * Test slug collision is automatically resolved with incrementing counter.
     */
    public function test_school_slug_collision_is_automatically_resolved(): void
    {
        $school1 = School::create([
            'name' => 'Greenwood Grammar School',
            'status' => SchoolStatus::Active,
        ]);

        $school2 = School::create([
            'name' => 'Greenwood Grammar School',
            'status' => SchoolStatus::Active,
        ]);

        $this->assertSame('greenwood-grammar-school', $school1->slug);
        $this->assertSame('greenwood-grammar-school-1', $school2->slug);
    }

    /**
     * Test slug collision handles soft deleted schools in single lookup.
     */
    public function test_school_slug_collision_handles_soft_deleted_schools_in_single_lookup(): void
    {
        $school1 = School::create([
            'name' => 'Metro Public School',
            'status' => SchoolStatus::Active,
        ]);

        $school1->delete();

        $school2 = School::create([
            'name' => 'Metro Public School',
            'status' => SchoolStatus::Active,
        ]);

        $this->assertSame('metro-public-school', $school1->slug);
        $this->assertSame('metro-public-school-1', $school2->slug);
    }

    /**
     * Test school slug must be strictly unique at database level.
     */
    public function test_school_slug_must_be_unique_at_database_level(): void
    {
        School::factory()->create(['slug' => 'unique-campus']);

        $this->expectException(QueryException::class);

        School::factory()->create(['slug' => 'unique-campus']);
    }

    /**
     * Test school code must be unique when provided.
     */
    public function test_school_code_must_be_unique(): void
    {
        School::factory()->create(['code' => 'SCH-9999']);

        $this->expectException(QueryException::class);

        School::factory()->create(['code' => 'SCH-9999']);
    }

    /**
     * Test school supports soft deletion and restoration.
     */
    public function test_school_supports_soft_deletion_and_restoration(): void
    {
        $school = School::factory()->create();

        $school->delete();

        $this->assertSoftDeleted('schools', ['id' => $school->id]);
        $this->assertNull(School::find($school->id));
        $this->assertNotNull(School::withTrashed()->find($school->id));

        $school->restore();
        $this->assertNotNull(School::find($school->id));
    }

    /**
     * Test SchoolSeeder is idempotent.
     */
    public function test_school_seeder_is_idempotent(): void
    {
        $this->seed(SchoolSeeder::class);
        $this->seed(SchoolSeeder::class);

        $count = School::where('slug', 'educore-academy')->count();
        $this->assertSame(1, $count);
    }

    /**
     * Test BelongsToSchool trait automatically filters records by current_school_id.
     */
    public function test_belongs_to_school_scope_filters_queries_by_tenant_context(): void
    {
        $schoolA = School::factory()->create();
        $schoolB = School::factory()->create();

        TestTenantRecord::withoutGlobalScope(SchoolScope::class)->create([
            'school_id' => $schoolA->id,
            'title' => 'Curriculum A',
        ]);
        TestTenantRecord::withoutGlobalScope(SchoolScope::class)->create([
            'school_id' => $schoolB->id,
            'title' => 'Curriculum B',
        ]);

        // When tenant context is School A
        app()->instance('current_school_id', $schoolA->id);
        $recordsA = TestTenantRecord::all();
        $this->assertCount(1, $recordsA);
        $this->assertSame('Curriculum A', $recordsA->first()->title);

        // When tenant context is School B
        app()->instance('current_school_id', $schoolB->id);
        $recordsB = TestTenantRecord::all();
        $this->assertCount(1, $recordsB);
        $this->assertSame('Curriculum B', $recordsB->first()->title);
    }

    /**
     * Test Super Admin can bypass tenant scope using withoutGlobalScope.
     */
    public function test_tenant_scope_can_be_bypassed_for_super_admin(): void
    {
        $schoolA = School::factory()->create();
        $schoolB = School::factory()->create();

        TestTenantRecord::withoutGlobalScope(SchoolScope::class)->create([
            'school_id' => $schoolA->id,
            'title' => 'Asset A',
        ]);
        TestTenantRecord::withoutGlobalScope(SchoolScope::class)->create([
            'school_id' => $schoolB->id,
            'title' => 'Asset B',
        ]);

        app()->instance('current_school_id', $schoolA->id);

        $allRecords = TestTenantRecord::withoutGlobalScope(SchoolScope::class)->get();
        $this->assertCount(2, $allRecords);
    }

    /**
     * Test BelongsToSchool automatically assigns current_school_id on model creation.
     */
    public function test_belongs_to_school_automatically_assigns_school_id_on_create(): void
    {
        $school = School::factory()->create();
        app()->instance('current_school_id', $school->id);

        $record = TestTenantRecord::create([
            'title' => 'Automated School Record',
        ]);

        $this->assertSame($school->id, $record->school_id);
    }
}

/**
 * Fixture model to test BelongsToSchool trait and SchoolScope.
 */
class TestTenantRecord extends Model
{
    use BelongsToSchool;

    protected $table = 'test_tenant_records';

    protected $guarded = [];
}
