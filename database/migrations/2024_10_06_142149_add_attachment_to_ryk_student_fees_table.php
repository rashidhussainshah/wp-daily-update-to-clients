<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentToRykStudentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ryk_student_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('ryk_student_fees', 'attachment')) {
                $table->string('attachment')->nullable()->after('status');  // Add the 'attachment' column
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ryk_student_fees', function (Blueprint $table) {
            if (Schema::hasColumn('ryk_student_fees', 'attachment')) {
                $table->dropColumn('attachment');
            }
        });
    }
}
