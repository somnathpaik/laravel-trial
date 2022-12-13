<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientSearchRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_search_records', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->index();
            $table->foreignId('client_id')->index();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->integer('popularity_count')->index();
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
        Schema::dropIfExists('client_search_records');
    }
}
