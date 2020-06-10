@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">{{$pageInfo}}</h3>
                </div>
              </div>
            </div>
            <div class="card-body">
                <label for="" class="form-control-label">Input Text</label>
                <input type="text" class="form-control">
                <br>
                <label for="" class="form-control-label">Input File</label>
                <input type="file" class="form-control" id="customFile">
                <br>
                <label for="" class="form-control-label">Select</label>
                <select name="" class="form-control select2" id="">
                    <option value="">---Option---</option>
                    <option value="">Option 1</option>
                    <option value="">Option 2</option>
                    <option value="">Option 3</option>
                </select>
                <br>
                <br>
                <label for="" class="form-control-label">Datepicker</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                    </div>
                    <input type="text" class="datepicker form-control">
                </div>                
                <br>
                <label for="" class="form-control-label">Textarea</label>
                <textarea name="" class="form-control"></textarea>
                <br>
                <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                <button class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
            </div>
        </div>
    </div>
</div>
@endsection