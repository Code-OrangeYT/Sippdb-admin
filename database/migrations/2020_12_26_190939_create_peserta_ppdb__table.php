<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesertaPpdbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_peserta_ppdb', function (Blueprint $table) {
            $table->id();

            //Penghasilam Ortu relasi
            $table->unsignedBigInteger('id_penghasilan_ortu');
            $table->foreign('id_penghasilan_ortu')
                ->references('id')
                ->on('tbl_penghasilan_ortu')
                ->onDelete('cascade');

            //Pekerjaan Ortu relasi
            $table->unsignedBigInteger('id_pekerjaan_ortu');
            $table->foreign('id_pekerjaan_ortu')
                ->references('id')
                ->on('tbl_pekerjaan_ortu')
                ->onDelete('cascade');

            $table->string('nama');
            $table->string('agama');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->longText('alamat');
            $table->string('no_telp');
            $table->string('asal_sekolah');
            $table->string('nama_ortu');
            $table->string('ijasah'); // File path to store ijasah file
            $table->string('kk'); // File path to store foto kk fi
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
        Schema::dropIfExists('tbl_peserta_ppdb');
    }
}
