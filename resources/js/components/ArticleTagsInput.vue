<template>
  <div>
    <!-- 
      bladeからformタグだとPOST送信できない
      コンポーネント中の'hidden'でタグ情報を送る　-->
    <input
      type="hidden"
      name="tags"
      :value="tagsJson"
    >
    <vue-tags-input
      v-model="tag"
      :tags="tags"
      placeholder="タグを5個まで入力できます"
      :autocomplete-items="filteredItems"
      :add-on-key="[13, 32]"
      @tags-changed="newTags => tags = newTags"
    />
  </div>
</template>

<script>
import VueTagsInput from '@johmun/vue-tags-input';

export default {
  components: {
    VueTagsInput,
  },
  data() {
    return {
      tag: '',
      tags: this.initialTags,
    };
  },
  props: {
    initialTags: {
      type: Array,
      default: [],
    },
    autocompleteItems: {
      type: Array,
      default: [],
    }
  },
  computed: {
    filteredItems() {
      return this.autocompleteItems.filter(i => {
        return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
      });
    },
    tagsJson() {
      // データtagsをJSON形式の文字列に変換したものを返しています
      return JSON.stringify(this.tags)
    },
  },
};
</script>
<style lang="css" scoped>
  .vue-tags-input {
    max-width: inherit;
  }
</style>
<style lang="css">
  .vue-tags-input .ti-tag {
    background: transparent;
    border: 1px solid #747373;
    color: #747373;
    margin-right: 4px;
    border-radius: 0px;
    font-size: 13px;
  }

  /* 表示されるタグの先頭に擬似要素"#"を付けるようにしています */
  .vue-tags-input .ti-tag::before {
    content: "#";
  }
</style>