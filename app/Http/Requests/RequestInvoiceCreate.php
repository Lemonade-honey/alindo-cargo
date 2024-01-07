<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestInvoiceCreate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "kota-asal"=> "required",
            "kota-tujuan"=> "required",
            "berat"=> ["required", "numeric"],
            "harga/kg"=> ["required","numeric"],
            "koli" => ["required", "min:1"],
            "keterangan-barang"=> "required",
            "pemeriksaan"=> ["required","in:0,1"],
            "nama-pengirim"=> ["required","regex:/^[a-zA-Z ]+$/"],
            "kontak-pengirim"=> ["required","numeric"],
            "nama-penerima"=> ["required","regex:/^[a-zA-Z ]+$/"],
            "kontak-penerima"=> ["required","numeric"],
            "alamat-penerima"=> "required",
            "ket_lainnya.*" => "nullable",
            "ket_biaya.*" => ["nullable", "numeric"],
            "biaya-kirim"=> ["required","numeric"],
            "total-biaya"=> ["required","numeric"]
        ];
    }
}
