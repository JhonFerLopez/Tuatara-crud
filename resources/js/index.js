export default {
	state: {
       usersUpdate: true ,
       userUpdate: null ,
	},
	getters: {
		getUpdateUsers(state){ //take parameter state
		  	return state.usersUpdate
		},
		getUpdateUser(state){
			return state.userUpdate
		}
	},
	actions: {
       addUser: (context, payload) => {
	      context.commit('ADD_USERS', payload)
	    },
	    addUserUpdate: (context, payload) => {
	      context.commit('UPDATE_USER', payload)
	    }
	},
	mutations: {
       	ADD_USERS: (state, payload) => {
	      	state.usersUpdate = payload
	    },
	    UPDATE_USER: (state, payload) => {
	      	state.usersUpdate = payload
	    }
	}
}