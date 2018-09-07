<template>
  <div>
    <Card>
      <Button style="margin: 10px 0;" type="primary" @click="articleForm">新增内容</Button>
      <Table border :loading="loading" :columns="columns" :data="tableData"></Table>
      <Page :current="current_page" :total="total_count" size="small" :page-size="page_size" show-total show-elevator @on-change="pageData" style="margin-top: 10px;"/>
    </Card>
  </div>
</template>

<script>
import { getArticleData } from '@/api/data'
import { mapMutations } from 'vuex'
export default {
  data () {
    return {
      loading: true,
      page_size: 10,
      current_page: 1,
      total_count: 0,
      columns: [
        {
          title: 'ID',
          key: 'id'
        },
        {
          title: 'Title',
          key: 'title'
        },
        {
          title: 'Category',
          key: 'cate_name'
        },
        {
          title: 'Photo',
          key: 'photo',
          render: (h, params) => {
            if (params.row.photo) {
              return h('img', {
                attrs: {
                  src: params.row.photo
                },
                style: {
                  width: '50px'
                }
              })
            }
          }
        },
        {
          title: 'Sort',
          key: 'sort',
          sortable: true
        },
        {
          title: 'PV',
          key: 'pv',
          sortable: true
        },
        {
          title: 'is_Top',
          key: 'is_top_desc',
          sortable: true
        },
        {
          title: 'Status',
          key: 'status_desc',
          sortable: true,
          render: (h, params) => {
            const color = params.row.status === 1 ? '#19be6b' : '#ff9900'
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
        name: 'article_form',
        params: {
          id
        },
        meta: {
          title: '内容编辑'
        }
      }
      this.addTag({
        route: route,
        type: 'push'
      })
      this.$router.push(route)
    },
    articleForm (params) {
      const route = {
        name: 'article_form',
        meta: {
          title: '新增内容'
        }
      }
      this.addTag({
        route: route,
        type: 'push'
      })
      this.$router.push(route)
    },
    pageData (page) {
      getArticleData(page).then(res => {
        this.loading = false
        const data = res.data
        this.tableData = data.list
        this.total_count = data.total
      })
    }
  },
  mounted () {
    getArticleData(this.current_page).then(res => {
      this.loading = false
      if (res && res.errcode === 0) {
        const data = res.data
        this.tableData = data.list
        this.total_count = data.total
      }
    })
  }
}
</script>

<style>

</style>
