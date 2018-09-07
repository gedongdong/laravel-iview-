<template>
  <div>
    <Card>
      <Button style="margin: 10px 0;" type="primary" @click="categoryForm">新增栏目</Button>
      <Table border :loading="loading" :columns="columns" :data="tableData"></Table>
    </Card>
  </div>
</template>

<script>
import { getCategoryData } from '@/api/data'
import { mapMutations } from 'vuex'
export default {
  data () {
    return {
      loading: true,
      columns: [
        {
          title: 'ID',
          key: 'id'
        },
        {
          title: 'Name',
          key: 'name'
        },
        {
          title: 'Sort',
          key: 'sort'
        },
        {
          title: 'Status',
          key: 'status_desc',
          sortable: true,
          render: (h, params) => {
            const color = params.row.status === 1 ? '#ff9900' : '#19be6b'
            return h('span', {
              style: {
                color: color
              }
            }, params.row.status_desc)
          }
        },
        {
          title: 'Action',
          key: 'action',
          width: 150,
          align: 'center',
          render: (h, params) => {
            return h('div', [
              h('Button', {
                props: {
                  type: 'primary',
                  size: 'small'
                },
                style: {
                  marginRight: '5px'
                },
                on: {
                  click: () => {
                    this.edit(params.row.id)
                  }
                }
              }, '编辑')
            ])
          }
        }
      ],
      tableData: []
    }
  },
  methods: {
    ...mapMutations([
      'addTag'
    ]),
    edit (id) {
      const route = {
        name: 'category_form',
        params: {
          id
        },
        meta: {
          title: '栏目编辑'
        }
      }
      this.addTag({
        route: route,
        type: 'push'
      })
      this.$router.push(route)
    },
    categoryForm (params) {
      const route = {
        name: 'category_form',
        meta: {
          title: '新增栏目'
        }
      }
      this.addTag({
        route: route,
        type: 'push'
      })
      this.$router.push(route)
    }
  },
  mounted () {
    getCategoryData().then(res => {
      this.loading = false
      this.tableData = res.data
    })
  }
}
</script>

<style>

</style>
