<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAttachmentNullableInRykStudentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ryk_student_fees', function (Blueprint $table) {
            $table->string('attachment')->nullable()->change();  // Make the column nullable
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
            $table->string('attachment')->nullable(false)->change();  // Revert to not nullable
        });
    }
}
