<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->timestamp('created_at')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('tenant_id', 'tenant_user_tenant_id_foreign')
                ->references('id')->on('tenant')
                ->onDelete('RESTRICT');

            $table->foreign('user_id', 'tenant_user_user_id_foreign')
                ->references('id')->on('user')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_user');
    }
}
