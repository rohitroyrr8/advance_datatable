<template>
   <div>
       <div class="panel">
           <h3>{{title}}</h3>
       </div>
       <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" v-model="query.search_column">
                        <option v-for="column in columns
                        " :value="column">{{column}}</option>
                    </select>
               </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" v-model="query.search_operator">
                        <option v-for="(value, key) in operators
                        " :value="key">{{value}}</option>
                    </select>
               </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                   <input type="search" class="form-control" v-model="query.search_input" @keyup="fetchIndexData()">
               </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="submit" class="form-control"
                     value="Filter" @click="fetchIndexData()">
                </div>
            </div>
       </div>
       <div style="background: white; padding: 20px">
         <table class="table table-hover table-striped ">
           <tr>
             <th v-for="column in columns" @click="toggleOrder(column)">
                <span>{{ column }}</span>
                <span v-if="column == query.column">
                  <span v-if="query.direction === 'desc'">&darr;</span>
                  <span v-else>&uarr;</span>
                </span>
             </th>
           </tr>
           <tr v-for="row in model.data">
             <td v-for="(value, key) in row">{{ value }}</td>
           </tr>
         </table>
         <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="dataTables_info" id="example_info" role="status" aria-live="polite">Displaying {{model.from}} to {{model.to}} from {{model.total}} entries</div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
                    <ul class="pagination">
                        <li class="paginate_button page-item previous " id="example_previous"><a href="#" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link" @click="prev()">Previous</a></li>
                        <!-- <li class="paginate_button page-item active"><a href="#" aria-controls="example" data-dt-idx="1" tabindex="0" class="page-link">1</a></li> -->
                        <li class="paginate_button page-item next" id="example_next"><a href="#" aria-controls="example" data-dt-idx="2" tabindex="0" class="page-link" @click="next()">Next</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
              <div class="dataTables_length" style=" float: right;">
                <label style="display: inline-flex">Show 
                  <select name="example_length" aria-controls="example" class="form-control form-control-sm" style="    margin: -2px 7px;" v-model="query.per_page" v-on:click="fetchIndexData()">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select>
                   entries
                 </label>
               </div>
            </div>
        </div>
        
       </div>
   </div>
</template>

<script> 
    import Vue from 'vue'
    import axios from 'axios'
    export default {
        props : ['source', 'title'],
        data() {
          return {
            model : {},
            columns : {},
            query : {
              page : 1,
              direction : 'desc',
              column : 'id',
              per_page : 10,
              search_column : 'id',
              search_operator : 'equal',
              search_input : ''
            },
            operators: {
              equal: '=',
              not_equal: '<>',
              less_than: '<',
              greater_than: '>',
              less_than_or_equal_to: '<=',
              greater_than_or_equal_to: '>=',
              in: 'IN',
              like: 'LIKE'
            }
          }
        },
        created() {
          this.fetchIndexData()
        },

        methods: {
          next(){
            if(this.model.next_page_url) {
              this.query.page++
              this.fetchIndexData()
            }
          },
          prev() {
             if(this.model.prev_page_url) {
              this.query.page--
              this.fetchIndexData()
            }
          },
          toggleOrder(column) {
            if(column === this.query.column) {
              //only change direction
              if(this.query.direction === 'desc') {
                this.query.direction = 'asc'
              } else {
                this.query.direction = 'desc'
              }
            } else {
              this.query.column = column
              this.query.direction = 'desc'  
            }
            this.fetchIndexData()
          },

          fetchIndexData() {
            var vm = this
            axios.get(`${this.source}?column=${this.query.column}&direction=${this.query.direction}&page=${this.query.page}&per_page=${this.query.per_page}&search_input=${this.query.search_input}&search_column=${this.query.search_column}&search_operator=${this.query.search_operator}`)
              .then(function(response) {
                Vue.set(vm.$data, 'model', response.data.model)
                Vue.set(vm.$data, 'columns', response.data.columns)
              })
              .catch(function(response) {
                console.log(response)
              })
          }
        }
    }
</script>
