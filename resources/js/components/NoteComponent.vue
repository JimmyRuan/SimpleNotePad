<template>
    <div class="row main justify-content-md-center">
        <div class="col-md-12">
            <h3>My Notepad</h3>
            <div class="col-md-12 justify-content-center">
                <input class="note-input form-control center"
                       v-bind:style="{background: currentNote.color.back, color: currentNote.color.color}"
                       v-model="currentNote.title" placeholder="Add note title">
            </div>
            <div class="col-md-12">
                <textarea class="note-input form-control center note-body"
                          v-bind:style="{
                          background: currentNote.color.back,
                          color: currentNote.color.color
                          }"
                          v-model="currentNote.content" placeholder="add note content"></textarea>
            </div>
            <div class="col-md-12">
                <div>
                    <i v-for="item in colors"
                       class="fa fa-circle pointer"
                       v-on:click="pickMainColor(item)"
                       v-bind:style="{color: item.back, background: 'lightblue'}"></i>
                </div>

                <div v-if="isEdit">
                    <button class="btn btn-primary" v-on:click="updateNote">Edit</button>
                    <button class="btn btn-primary" v-on:click="cancelEdit">Cancel</button>
                </div>

                <button v-else class="btn btn-primary" v-on:click="store">Add</button>
            </div>
        </div>


        <div v-for="noteItem in noteItems"
             v-if="noteItem.id !== currentNote.id"
             v-bind:style="{color: noteItem.color.color, background: noteItem.color.back}"
             class="col-md-3 card note-item mt-3 ml-3">
            <div class="card-body">
                <h5 class="card-title">{{noteItem.title}}</h5>
                <p class="card-text">{{noteItem.excerpt}}</p>
                <div>
                    <i v-for="colorItem in colors"
                       class="fa fa-circle pointer"
                       v-on:click="pickItemColor(colorItem, noteItem.id)"
                       v-bind:style="{color: colorItem.back, background: noteItem.color.back}"></i>
                </div>
                <div>
                    <i class="fa fa-trash pointer"
                        v-on:click="deleteNote(noteItem.id)"
                        aria-hidden="true"></i>

                    <i class="fa fa-edit pointer"
                       v-on:click="editNote(noteItem)"
                       aria-hidden="true"></i>

                </div>
            </div>
        </div>

    </div>
</template>

<script>

    const defaultNote = {
        id: null,
        title: null,
        content: null,
        color: {back: '#7FDBFF', color: 'hsla(197, 100%, 20%, 1.0)'}
    };
    export default {
        props: {
           notes: Array
        },
        data() {
            return {
                currentNote: defaultNote,
                noteItems: [],
                colors: [
                    {back: '#001f3f', color: 'hsla(210, 100%, 75%, 1.0)'},
                    {back: '#0074D9', color: 'hsla(210, 100%, 75%, 1.0)'},
                    {back: '#FF851B', color: 'hsla(28, 100%, 20%, 1.0)'},
                    {back: '#111', color: '#ddd'},
                    {back: '#B10DC9', color: 'hsla(292, 88%, 82%, 1.0)'},
                    {back: '#01FF70', color: 'hsla(146, 100%, 20%, 1.0)'},
                    {back: '#FFDC00', color: 'hsla(52, 100%, 20%, 1.0)'},
                    {back: '#7FDBFF', color: 'hsla(197, 100%, 20%, 1.0)'}
                ],
                isEdit: false
            }

        },
        methods: {
            store: function(event){
                const _this = this;

                axios.post('/notes', {
                    'title': this.currentNote.title,
                    'content': this.currentNote.content,
                    'color': JSON.stringify(this.currentNote.color)
                })
                .then(function(response) {
                    const note = reformatNote(response.data.data);
                    _this.noteItems.unshift(note);
                    _this.currentNote.title = null;
                    _this.currentNote.content = null;
                })
                .catch(function(error) {
                    console.error(error);
                });
            },
            showNotes: function(){
                const _this = this;
                axios.get('/notes/list')
                .then(function(response) {
                    _this.noteItems = response.data.data.map(
                        item => reformatNote(item)
                    );
                })
                .catch(function(error) {
                    console.error(error);
                });
            },
            pickMainColor: function(color){
                this.currentNote.color = color;

            },
            pickItemColor: function(color, index){

                this.noteItems = this.noteItems.map(function(item){
                    if(item.id === index){
                        return _.merge(item, {color: color});
                    }
                    return item;
                });

                axios.patch(`/notes/${index}/color`, {
                    'color': JSON.stringify(color)
                })
                .catch(function(error) {
                    console.error(error);
                });

            },
            deleteNote: function(index){
                this.noteItems = this.noteItems.filter(function(item){
                    return item.id !== index;
                });

                axios.delete(`/notes/${index}`)
                .catch(function(error) {
                    console.error(error);
                });
            },
            editNote: function(note){
                console.log('editing note now ' + note.index);
                this.isEdit = true;
                this.currentNote = note;
            },
            updateNote: function(){
                const _this = this;
                axios.patch(`/notes/${this.currentNote.id}`, {
                    'id': this.currentNote.id,
                    'title': this.currentNote.title,
                    'content': this.currentNote.content,
                    'color': JSON.stringify(this.currentNote.color)
                })
                .then(function(response){
                    _this.noteItems = _this.noteItems.map(function(item){
                        if(item.id === _this.currentNote.id)
                        {
                            return _.merge(_this.currentNote, {excerpt: getExcerpt(_this.currentNote.content)});
                        }
                        return item;
                    });
                    _this.cancelEdit();
                })
                .catch(function(error) {
                    console.error(error);
                });
            },
            cancelEdit: function(){
                this.isEdit = false;
                this.currentNote = defaultNote;
            }
        },
        created() {
            this.showNotes();
        }
    }

    const getExcerpt = function (content){
        if(content.length > 200)
        {
            return content.substring(0, 200) + '...';
        }

        return content;
    };

    const reformatNote = function (note){
        return _.merge(note, {
            excerpt: getExcerpt(note.content),
            color: JSON.parse(note.color)
        });
    };
</script>

<style>
    .note-input{
        width: 100%;
        max-width: 500px;
        text-align: center;
    }

    .note-item{
        cursor: pointer;
        width: 18rem;
    }

    .main{
        background-color: lightblue;
        padding: 50px;
        color: black;
    }

    .card-text{
        text-align: justify;
    }

    .pointer {
        cursor: pointer;
    }

    .center{
        margin: auto;
    }

    .note-body{
        height: 250px;
    }

    i:hover{
        font-size: 2em;
    }
</style>
