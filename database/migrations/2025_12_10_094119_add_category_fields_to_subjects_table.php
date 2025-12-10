use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // nếu chưa có thì thêm
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->string('icon')->nullable()->after('description');
            $table->string('color', 20)->default('#2563eb')->after('icon');
            $table->boolean('is_active')->default(true)->after('color');

            $table->foreign('parent_id')
                ->references('id')->on('subjects')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'icon', 'color', 'is_active']);
        });
    }
};
