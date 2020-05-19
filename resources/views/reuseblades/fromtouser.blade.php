          <div class="col-md-12" >
                  <form>
                 <div class="col-md-3">
                  
                <label>(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from" id="from">
              </div>
              <div class="col-md-3">
                <label>(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to" id="to">
              </div>
              <div class="col-md-3">

                <label>User Name</label>
                 <select class="form-control" name="type" id="type">
                   <option {{ isset($_GET['type']) ? $_GET['type']: '' }} value="">---Select---</option>
                  <option value="All">All</option>
                   @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                   @endforeach   
                 </select>
              </div>
                  <div class="col-md-2">
                <label>submit</label>
                <button onclick="getdeatis()" value="Fetch" class="form-control btn btn-primary">Fetch</button>
              </div>
                </form>
                  </div> 