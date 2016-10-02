{{-- Contract Assignments --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Contract Assignments
                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Select which contracts this product request shall be associated with.">
                        </i>
                    </h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

                    <div class="col-md-12">
                        <!-- Contracts assigned Form Input -->
                        <select multiple="multiple" name="contracts_assigned[]" class="form-control" id="contracts_assigned_input">
                            @foreach ($unassigned_contracts as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                            @if(isset($product_request))
                                @if (count($product_request->contracts))
                                    @foreach ($product_request->contracts as $assignedContract)
                                        <option value="{{ $assignedContract->id }}" selected>{{ $assignedContract->name }}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>

                        <br/>
                        <button class="btn btn-success" id="select-all">Assign All</button>
                        <button class="btn btn-black" id="deselect-all">Unassign All</button>
                    </div>

				</div>

			</div>

        </div>

    </div>
</div>
