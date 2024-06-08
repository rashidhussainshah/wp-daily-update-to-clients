<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->comment('client_id from users will use here');
            $table->string('phone')->nullable();
            $table->string('website_url');
            $table->string('website_email_or_username')->nullable()->comment('website login admin email/username');
            $table->string('website_login_password')->nullable()->comment('website login admin password');
            $table->string('git_repo_link')->nullable();
            $table->string('skype')->nullable();
            $table->string('slack')->nullable();
            $table->foreignId('user_id');
            $table->string('phone')->nullable();
            $table->string('website_url');
            $table->string('website_email_or_username')->comment('website login admin email/username');
            $table->string('website_login_password')->comment('website login admin password');
            $table->string('git_repo_link')->nullable();
            $table->string('jira_project_link')->nullable();
            $table->string('skype')->nullable();
            $table->text('server_login_information')->nullable()->comment('SSH or FTP or Cpanel login');
            $table->text('server_login_files')->nullable()->comment('.ssh or .ppk or any other file');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_information');
    }
}
