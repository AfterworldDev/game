 * Requirements: 
 * - GameObject that would represent any character.
 * */

using UnityEngine;
using System.Collections;

public class SpawnHere  : MonoBehaviour {
	
   // public Transform target = null;
	public GameObject some_character;
    public int max_chars = 200;
	
	static int count = 0;
    static float lastTime = 0;
    

    void Start()
    {
        lastTime = Time.time;
    }

    void Update()
    {
        if(count < max_chars)
        {
			if (Time.time - lastTime > 0.1f)
            {
				//Spawn Here: assuming have target and have a prefab
                if(some_character != null) Instantiate(some_character, this.transform.position, this.transform.rotation);
               
                lastTime = Time.time;
                count++;
            }
        }
    }
}
