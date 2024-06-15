import {SelectControl} from "@wordpress/components"
import {useSelect} from "@wordpress/data";
import './styles.scss';
import apiFetch from '@wordpress/api-fetch';
import {useState , useEffect} from "react";
wp.blocks.registerBlockType('tpblockplugin/team-preview' , {
    title: "Team memeber preview" , 
    category: 'common' , 
    icon: "welcome-view-site" , 
    description:"Allow users to navigate through your team members!",
    attributes:{
        postID: {type:"string"}
    },
    edit: editHTML,
    save: function(props){
        return null
    }
})

function editHTML(props){

    const [thePreview , setPreview] = useState('');

    useEffect(()=>{
        async function getPreview(){
            const results = await apiFetch({
                path:`tpblock-plugin/v1/generatePreview?postID=${props.attributes.postID}`,
                method: 'GET'
            })

            setPreview(results);
        } 
        getPreview()
    },[props.attributes.postID])

    const result = useSelect(member =>{
        return  member('core').getEntityRecords('postType' , 'post' , {per_page:-1 })
    })
    if(result == undefined) return <h4>Loading...</h4> 
    return(
        <div>
        <SelectControl label="Choose A Team Member" 
                    options={[
                        {label:"Select Member" , value:"notSelected"},
                        ...result.map(single=>{
                            return {label:single.title.rendered , value:single.id}
                        })
                        ]}
                    onChange={(value)=>{props.setAttributes({postID : value})}}
                    value={props.attributes.postID}
        
            />
        <div dangerouslySetInnerHTML={{__html:thePreview}}></div>
        </div>
    )
}