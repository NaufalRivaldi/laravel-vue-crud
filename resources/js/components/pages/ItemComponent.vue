<template>
  <div>
    <h3>Simple CRUD Item</h3>
    <hr>
    <button class="btn btn-primary btn-sm" @click="addData">+ Add</button>

    <!-- table -->
    <table class="table table-bordered table-striped mt-3">
      <thead>
        <tr>
          <th>Id</th>
          <th>Name</th>
          <th>Qty</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item.id">
          <td>{{ item.id }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.qty }}</td>
          <td>{{ item.description }}</td>
          <td>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-primary btn-sm" @click="editData(item.id)">Edit</button>
              <button type="button" class="btn btn-danger btn-sm" @click="deleteData(item.id)">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <!-- table -->
    
    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="">Name</label>
              <div v-if="errorMsg.name == ''">
                <input type="text" v-model="name" class="form-control">
              </div>
              <div v-else>
                <input type="text" v-model="name" class="form-control is-invalid">
              </div>

              <!-- error -->
              <small class="text-danger">{{ errorMsg.name }}</small>
            </div>
            <div class="form-group">
              <label for="">Qty</label>
              <div v-if="errorMsg.qty == ''">
                <input type="number" v-model="qty" class="form-control">
              </div>
              <div v-else>
                <input type="number" v-model="qty" class="form-control is-invalid">
              </div>

              <!-- error -->
              <small class="text-danger">{{ errorMsg.qty }}</small>
            </div>
            <div class="form-group">
              <label for="">Description</label>
              <textarea v-model="description" rows="5" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="storeData">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
  </div>
</template>

<script>
import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);

export default {
  data(){
    return {
      // ==========================================
      // form data
      // ==========================================
      id: null,
      name: '',
      qty: '',
      description: '',

      // ==========================================
      // get data
      // ==========================================
      items: [],

      // ==========================================
      // error message
      // ==========================================
      errorMsg: {
        name: '',
        qty: '',
      }
    }
  }, 
  methods: {
    showModal(){
      $('#formModal').modal('show');
    },

    cleanInput(){
      this.name = '';
      this.qty = '';
      this.description = '';

      $('#formModal').modal('hide');
    },

    addData(){
      this.showModal();
      this.id = null;
    },

    getData(){
      axios.get('/item/get/data')
      .then((response)=>{
        this.items = response.data;
      })
      .catch((error)=>{
        console.log(error);
      });
    },

    storeData(){
      let data = {
        id          : this.id,
        name        : this.name,
        qty         : this.qty,
        description : this.description
      };

      if(this.id == null){
        axios.post('/item', data)
        .then((response)=>{
          if(response.data.status == false){
            this.errorMsg.name = response.data.error.name;
            this.errorMsg.qty = response.data.error.qty;
          }else{
            this.errorMsg.name = '';
            this.errorMsg.qty = '';
  
            this.cleanInput();
          }
        })
        .catch((error)=>{
          console.log(error);
        });
      }else{
        axios.put('/item/'+this.id, data)
        .then((response)=>{
          if(response.data.status == false){
            this.errorMsg.name = response.data.error.name;
            this.errorMsg.qty = response.data.error.qty;
          }else{
            this.errorMsg.name = '';
            this.errorMsg.qty = '';
  
            this.cleanInput();
          }
        })
        .catch((error)=>{
          console.log(error);
        });
      }

      this.getData();
    },

    editData(id){
      this.showModal();
      axios.get('/item/'+id)
      .then((response)=>{
        this.id = id;
        this.name = response.data.name;
        this.qty = response.data.qty;
        this.description = response.data.description;
      })
      .catch((error)=>{
        console.log(error);
      });
    },

    deleteData(id){
      this.$confirm("Are you sure?")
      .then(() => {
        axios.delete('/item/'+id)
        .then((response)=>{
          this.getData();
        })
        .catch((error)=>{
          console.log(error);
        });
      })
      .catch(() => {
        // 
      });
    },
  },

  mounted(){
    this.getData();
  }
}
</script>