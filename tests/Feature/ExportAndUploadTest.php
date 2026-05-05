<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Entity;
use App\Models\Infrastructure;
use App\Models\BreakdownLog;

class ExportAndUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_pdf_with_filters()
    {
        $super = User::factory()->create(['role' => 'superadmin']);

        $entity = Entity::create(['name' => 'Test Entity', 'code' => 'TST']);

        $infra = Infrastructure::create([
            'entity_id' => $entity->id,
            'category' => 'equipment',
            'code_name' => 'EQ-1',
            'type' => 'crane',
            'quantity' => 1,
            'status' => 'breakdown',
        ]);

        BreakdownLog::create([
            'infrastructure_id' => $infra->id,
            'issue_detail' => 'Test issue',
            'repair_status' => 'reported',
            'vendor_pic' => null,
            'created_by' => $super->id,
            'updated_by' => $super->id,
        ]);

        $response = $this->actingAs($super)->get('/admin/export/process?format=pdf');

        $response->assertStatus(200);
        $response->assertSee('Memproses Export');
    }

    public function test_export_with_many_records()
    {
        $super = User::factory()->create(['role' => 'superadmin']);
        $entity = Entity::create(['name' => 'Load Entity', 'code' => 'LD']);

        // modest load to keep test time reasonable
        for ($i = 0; $i < 100; $i++) {
            $infra = Infrastructure::create([
                'entity_id' => $entity->id,
                'category' => 'equipment',
                'code_name' => "EQ-{$i}",
                'type' => 'generic',
                'quantity' => 1,
                'status' => $i % 3 === 0 ? 'breakdown' : 'available',
            ]);

            for ($j = 0; $j < 5; $j++) {
                BreakdownLog::create([
                    'infrastructure_id' => $infra->id,
                    'issue_detail' => "issue {$i}-{$j}",
                    'repair_status' => $j % 4 === 0 ? 'reported' : 'on_progress',
                    'vendor_pic' => 'VP',
                    'created_by' => $super->id,
                    'updated_by' => $super->id,
                ]);
            }
        }

        $response = $this->actingAs($super)->get('/admin/export/process?format=excel');
        $response->assertStatus(200);
        $response->assertSee('LAPORAN KESIAPAN ALAT PELABUHAN');
    }

    public function test_document_proof_upload_stores_file()
    {
        Storage::fake('public');

        $super = User::factory()->create(['role' => 'superadmin']);
        $entity = Entity::create(['name' => 'Upload Entity', 'code' => 'UP']);

        $infra = Infrastructure::create([
            'entity_id' => $entity->id,
            'category' => 'equipment',
            'code_name' => 'EQ-UP',
            'type' => 'generic',
            'quantity' => 1,
            'status' => 'breakdown',
        ]);

        $log = BreakdownLog::create([
            'infrastructure_id' => $infra->id,
            'issue_detail' => 'issue upload',
            'repair_status' => 'on_progress',
            'vendor_pic' => 'VP',
            'created_by' => $super->id,
            'updated_by' => $super->id,
        ]);

        $file = UploadedFile::fake()->create('proof.pdf', 100);

        $response = $this->actingAs($super)->followingRedirects()->put(route('admin.breakdowns.update', $log->id), [
            'repair_status' => 'on_progress',
            'document_proof' => $file,
        ]);

        $response->assertStatus(200);

        $fresh = BreakdownLog::find($log->id);
        $this->assertNotNull($fresh->document_proof);
        Storage::disk('public')->assertExists($fresh->document_proof);
    }
}
