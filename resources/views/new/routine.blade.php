@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">Doctor Information</h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group er-input">
                <label class="col-sm-4">From:</label>
                <div class="col-sm-8">
                  <input type="date" value="" class="form-control" />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group er-input">
                <label class="col-sm-4">To:</label>
                <div class="col-sm-8">
                  <input type="date" value="" class="form-control" />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group er-input">
                <label class="col-sm-4">Specialization:</label>
                <div class="col-sm-8">
                  <select class="form-control">
                    <option value="">select specialization</option>
                    <option value="Test">Test</option>
                    <option value="dfsefdsf">dfsefdsf</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group er-input">
                <label class="col-sm-4">Doctor:</label>
                <div class="col-sm-8">
                  <select class="form-control">
                    <option value="">select Doctor</option>
                    <option value="Test">Test</option>
                    <option value="dfsefdsf">dfsefdsf</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group er-input">
                <label class="col-sm-4">Duration:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="" />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group er-input">
                <label class="col-sm-4">Status:</label>
                <div class="col-sm-8">
                  <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                    <input type="radio" name="" class="custom-control-input bg-primary" />
                    <label class="custom-control-label"> Active </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">Doctor Availability</h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="res-table">
            <table class="table table-bordered table-responsive-md table-striped text-center">
              <thead>
                <tr>
                  <th>Days</th>
                  <th>Morning Shift</th>
                  <th>Lunch</th>
                  <th>Evening Shift</th>
                  <th>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label">Off</label>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Sunday</td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label"></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Monday</td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label"></label>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>Tuesday</td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label"></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Wednasday</td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label"></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Thursday</td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label"></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Friday</td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label"></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Saturday</td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-row">
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4 p-0">Start</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                      <div class="col-sm-12 col-lg-6 er-input">
                        <label class="col-sm-4">End</label>

                        <input type="time" class="form-control col-sm-7" id="exampleInputtime" value="13:45" />
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" />
                      <label class="custom-control-label"></label>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
